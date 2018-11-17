(function(exports){

var o = {};

    o.isMainThread_ = function(callback) {
        callback.complete(true);
    };

    o.notifyDispatchQueue_ = function(callback) {
        var runQueuedEvent = this.$GLOBAL$.com_codename1_htmlform_HTMLForm.runQueuedEvent_$async;
                             
        setInterval(function(){runQueuedEvent()}, 0);
        callback.complete(null);
    };

    o.isSupported_ = function(callback) {
        callback.complete(true);
    };

exports.com_codename1_htmlform_HTMLFormNative= o;

})(cn1_get_native_interfaces());
