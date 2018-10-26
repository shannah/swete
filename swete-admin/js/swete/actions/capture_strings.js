//require <jquery.packed.js>
(function() {
    var $ = jQuery;
    var jobStarting=false;
    var jobEl, noJobEl, jobStartingEl;
    var jobID = null;
    var getCurrentJob = function(siteID) {
        return new Promise(function(resolve, reject) {
            $.get(DATAFACE_SITE_HREF, {
                '-action' : 'swete_capture_strings',
                '-table' : 'websites',
                '--progress' : '1',
                'website_id' : siteID
            }, function(res) {
                resolve(res);
            })
        });

    };
    
    
    var updateCurrentJob = function(siteID) {
        getCurrentJob(siteID).then(updateJob);
    };
    
    var updateJob = function(job) {
        if (jobStarting) {
            $(jobStartingEl).show();
            $(jobEl).hide();
            $(noJobEl).hide();
            
        } else {
            $(jobStartingEl).hide();
            if (job && !job.cancelled && !job.complete) {
                jobID = job.page_refresh_job_id;
                $(noJobEl).hide();
                $(jobEl).show();
                $('.progress', jobEl).progressbar({
                  value: Math.round(parseFloat(job.num_pages_completed) / parseFloat(job.num_pages_scheduled) * 100.0)
                });
                $('.start-time', jobEl).text(job.start_time);
                $('.completed', jobEl).text(job.num_pages_completed);
                $('.scheduled', jobEl).text(job.num_pages_scheduled);
            } else {
                $(jobEl).hide();
                $(noJobEl).show();
            }
        }
    };
    
    var createJob = function(siteID) {
        return new Promise(function(resolve, reject) {
            $.post(DATAFACE_SITE_HREF, {
                '-action' : 'swete_capture_strings',
                '-table' : 'websites',
                'website_id' : siteID
            }, function(res) {
                if (res.error) {
                    reject(res);
                } else {
                    resolve(res);
                }
            });
        });
    };
    
    var startJob = function(siteID, jobID) {
        return new Promise(function(resolve, reject) {
            $.post(DATAFACE_SITE_HREF, {
                '-action' : 'swete_capture_strings',
                '-table' : 'websites',
                'website_id' : siteID,
                'job_id' : jobID
            }).always(function(res) {
                jobStarting = false;
                resolve(res);
            });
        });
    };
    
    var cancelJob = function(siteID, jobID) {
        return new Promise(function(resolve, reject) {
            $.post(DATAFACE_SITE_HREF, {
                '-action' : 'swete_capture_strings',
                '-table' : 'websites',
                'website_id' : siteID,
                'job_id' : jobID,
                '--cancel' : 1
            }).always(function(res) {
                resolve(res);
            });
        });
    };
    
    $(document).ready(function() {
        jobEl = $('#job');
        noJobEl = $('#no-job');
        jobStartingEl = $('#job-starting');
        var siteID = $('input#swete-site-id').val();
        setInterval(function(){updateCurrentJob(siteID);}, 2000);
        
        $('button.cancel', jobEl).click(function() {
            if (jobID) {
                var btn = $(this);
                btn.attr('disabled', 'disabled');
                cancelJob(siteID, jobID).then(function(res) {
                    btn.removeAttr('disabled');
                    jobID = null;
                });
            } 
        });
        
        $('button.start', noJobEl).click(function() {
            var btn = $(this);
            btn.attr('disabled', 'disabled');
            jobStarting = true;
            createJob(siteID)
                .then(function(job) {
                    jobStarting = false;
                    jobID = job.page_refresh_job_id;
                    startJob(siteID, jobID);
                    updateJob(job);
                    btn.removeAttr('disabled');
                })
                .catch(function(e) {
                    console.log("Failed to create job." + e);
                });
        });
    });
    
})();