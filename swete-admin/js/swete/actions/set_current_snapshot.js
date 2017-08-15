//require <jquery.packed.js>
(function() {
    var $ = jQuery;

    function setCurrentSnapshot(snapshotId, onComplete) {
        var q = {
            'snapshot_id' : snapshotId,
            '-action' : 'set_current_snapshot',
            '-table' : 'snapshots'
        };
        $.post(DATAFACE_SITE_HREF, q, function(res) {
            if (onComplete) {
                onComplete(res);
            }
        });
    }

    $(document).ready(function() {
        $('button.set-current-snapshot-btn').click(function() {
            var btn = this;
            $(this).attr('disabled', true);
            var snapshotId = $(this).attr('data-snapshot-id');
            setCurrentSnapshot(snapshotId, function(res) {
                $(btn).removeAttr('disabled');
                if (res.code == 200) {
                    window.location.reload(true);
                } else {
                    alert("Failed to set current snapshot: "+res.message);
                }
            });
        });
    });
})();
