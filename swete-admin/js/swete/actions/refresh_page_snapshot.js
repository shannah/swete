//require <jquery.packed.js>
(function(){
    var $ = jQuery;

    /**
     * @param Number snapshotId The snapshot ID
     * @param String page The page to snapshot
     * @param function onComplete callback called when snapshot is complete.
     */
    function refreshSnapshot(snapshotId, page, onComplete) {
        var q = {
            'snapshot_id' : snapshotId,
            'page' : page,
            '-action' : 'refresh_page_snapshot',
            '-table' : 'snapshots'
        };
        $.post('index.php', q, function(res) {
            onComplete(res);
        });
    }

    /**
     * @param boolean chain Whether to refresh next row when this row is done.
     * @param boolean force Whether to force a refresh even if page is
     *  already snapshotted.
     * @param function onComplete Callback on completion of chain.
     */
    function refreshRow(tr, chain, force, onComplete) {
        var snapshotId = $(tr).attr('data-snapshot-id');
        var page = $(tr).attr('data-page');
        var statusCode = $(tr).attr('data-status-code');
        console.log("Status code is ", statusCode);
        if (!force && parseInt(statusCode) == 200) {
            var next = $(tr).next();
            if (next.length > 0) {
                refreshRow(next[0], chain, force, onComplete);
            } else {
                if (onComplete) {
                    onComplete();
                }
            }
            return;
        }
        $('span.row-progress', tr).show();
        $('button.refresh-snapshot', tr).attr('disabled', true);
        $('button.delete-from-snapshot', tr).attr('disabled', true);
        refreshSnapshot(snapshotId, page, function(res) {
            console.log("Refreshing snapshot for page ", page);
            $('span.row-progress').hide();
            $('button.refresh-snapshot', tr).removeAttr('disabled');
            $('button.delete-from-snapshot', tr).removeAttr('disabled');
            $(tr).attr('data-status-code', res.snapshotStatusCode);
            $(tr).attr('data-status-message', res.snapshotStatusMessage);
            $('.snapshot-status-message', tr).html(res.snapshotStatusMessage);
            if (chain) {
                var next = $(tr).next();
                if (next.length > 0) {
                    refreshRow(next[0], chain, force, onComplete);
                } else {
                    if (onComplete) {
                        onComplete();
                    }
                }
            } else {
                if (onComplete) {
                    onComplete();
                }
            }
        });
    }

    /**
     * Refreshes all snapshots in the tree rooted at root
     */
    function refreshRows(root, force, onComplete) {
        console.log("in refreshRows", root, force, onComplete);
        var first = $('tr[data-snapshot-id]', root).first();
        if (first.length > 0) {
            refreshRow(first, true, force, onComplete);
        } else {
            if (onComplete) {
                onComplete();
            }
        }
    }


    $(document).ready(function() {
        // When we load the page we will try to create a snapshot for all
        // pages that haven't been snapshotted yet.

        $('table.swete-snapshots').each(function() {
            $('#snapshot-progress').show();
            $('button.refresh-all-snapshots').attr('disabled', true);
            /*
            refreshRows(this, false, function() {
                console.log("Finished refreshing rows");
                $('#snapshot-progress').hide();
                $('button.refresh-all-snapshots').removeAttr('disabled');

            });
            */
        })
        // If the user clicks the refresh snapshot button it will
        // force a refresh of the full snapshot whether or not a page
        // has already been snapshotted
        $('button.refresh-all-snapshots').click(function() {
            $('#snapshot-progress').show();
            $('table.swete-snapshots').each(function() {
                $('button.refresh-all-snapshots').attr('disabled', true);
                refreshRows(this, true, function() {
                    $('#snapshot-progress').hide();
                    $('button.refresh-all-snapshots').removeAttr('disabled');
                    alert("Snapshot complete");
                });
            });

        });
    });

})();
