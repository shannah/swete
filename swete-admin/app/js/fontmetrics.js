
// This flag affects drawString.  If it is true, then drawString will use the alphabetic text baseline
// Otherwise it will use the top text baseline.  We used to use the top text baseline,
// but there are inconsistencies between Firefox and Chrome.  Chrome seems to add an offset.
// If we use the alphabetic baseline and simply add the ascent and leading, then we 
// get more consistent results.
// I am leaving this as a javacript flag to make it easier to toggle and experiment
// at runtime.
window.cn1_use_baseline_text_rendering =true;

(function () {

  if ( typeof window.CustomEvent === "function" ) return false;

  function CustomEvent ( event, params ) {
    params = params || { bubbles: false, cancelable: false, detail: undefined };
    var evt = document.createEvent( 'CustomEvent' );
    evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
    return evt;
   }

  CustomEvent.prototype = window.Event.prototype;

  window.CustomEvent = CustomEvent;
})();


/*
 * Copyright 2016 Small Batch, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
/* Web Font Loader v1.6.26 - (c) Adobe Systems, Google. License: Apache 2.0 */(function(){function aa(a,b,c){return a.call.apply(a.bind,arguments)}function ba(a,b,c){if(!a)throw Error();if(2<arguments.length){var d=Array.prototype.slice.call(arguments,2);return function(){var c=Array.prototype.slice.call(arguments);Array.prototype.unshift.apply(c,d);return a.apply(b,c)}}return function(){return a.apply(b,arguments)}}function p(a,b,c){p=Function.prototype.bind&&-1!=Function.prototype.bind.toString().indexOf("native code")?aa:ba;return p.apply(null,arguments)}var q=Date.now||function(){return+new Date};function ca(a,b){this.a=a;this.m=b||a;this.c=this.m.document}var da=!!window.FontFace;function t(a,b,c,d){b=a.c.createElement(b);if(c)for(var e in c)c.hasOwnProperty(e)&&("style"==e?b.style.cssText=c[e]:b.setAttribute(e,c[e]));d&&b.appendChild(a.c.createTextNode(d));return b}function u(a,b,c){a=a.c.getElementsByTagName(b)[0];a||(a=document.documentElement);a.insertBefore(c,a.lastChild)}function v(a){a.parentNode&&a.parentNode.removeChild(a)}
function w(a,b,c){b=b||[];c=c||[];for(var d=a.className.split(/\s+/),e=0;e<b.length;e+=1){for(var f=!1,g=0;g<d.length;g+=1)if(b[e]===d[g]){f=!0;break}f||d.push(b[e])}b=[];for(e=0;e<d.length;e+=1){f=!1;for(g=0;g<c.length;g+=1)if(d[e]===c[g]){f=!0;break}f||b.push(d[e])}a.className=b.join(" ").replace(/\s+/g," ").replace(/^\s+|\s+$/,"")}function y(a,b){for(var c=a.className.split(/\s+/),d=0,e=c.length;d<e;d++)if(c[d]==b)return!0;return!1}
function z(a){if("string"===typeof a.f)return a.f;var b=a.m.location.protocol;"about:"==b&&(b=a.a.location.protocol);return"https:"==b?"https:":"http:"}function ea(a){return a.m.location.hostname||a.a.location.hostname}
function A(a,b,c){function d(){k&&e&&f&&(k(g),k=null)}b=t(a,"link",{rel:"stylesheet",href:b,media:"all"});var e=!1,f=!0,g=null,k=c||null;da?(b.onload=function(){e=!0;d()},b.onerror=function(){e=!0;g=Error("Stylesheet failed to load");d()}):setTimeout(function(){e=!0;d()},0);u(a,"head",b)}
function B(a,b,c,d){var e=a.c.getElementsByTagName("head")[0];if(e){var f=t(a,"script",{src:b}),g=!1;f.onload=f.onreadystatechange=function(){g||this.readyState&&"loaded"!=this.readyState&&"complete"!=this.readyState||(g=!0,c&&c(null),f.onload=f.onreadystatechange=null,"HEAD"==f.parentNode.tagName&&e.removeChild(f))};e.appendChild(f);setTimeout(function(){g||(g=!0,c&&c(Error("Script load timeout")))},d||5E3);return f}return null};function C(){this.a=0;this.c=null}function D(a){a.a++;return function(){a.a--;E(a)}}function F(a,b){a.c=b;E(a)}function E(a){0==a.a&&a.c&&(a.c(),a.c=null)};function G(a){this.a=a||"-"}G.prototype.c=function(a){for(var b=[],c=0;c<arguments.length;c++)b.push(arguments[c].replace(/[\W_]+/g,"").toLowerCase());return b.join(this.a)};function H(a,b){this.c=a;this.f=4;this.a="n";var c=(b||"n4").match(/^([nio])([1-9])$/i);c&&(this.a=c[1],this.f=parseInt(c[2],10))}function fa(a){return I(a)+" "+(a.f+"00")+" 300px "+J(a.c)}function J(a){var b=[];a=a.split(/,\s*/);for(var c=0;c<a.length;c++){var d=a[c].replace(/['"]/g,"");-1!=d.indexOf(" ")||/^\d/.test(d)?b.push("'"+d+"'"):b.push(d)}return b.join(",")}function K(a){return a.a+a.f}function I(a){var b="normal";"o"===a.a?b="oblique":"i"===a.a&&(b="italic");return b}
function ga(a){var b=4,c="n",d=null;a&&((d=a.match(/(normal|oblique|italic)/i))&&d[1]&&(c=d[1].substr(0,1).toLowerCase()),(d=a.match(/([1-9]00|normal|bold)/i))&&d[1]&&(/bold/i.test(d[1])?b=7:/[1-9]00/.test(d[1])&&(b=parseInt(d[1].substr(0,1),10))));return c+b};function ha(a,b){this.c=a;this.f=a.m.document.documentElement;this.h=b;this.a=new G("-");this.j=!1!==b.events;this.g=!1!==b.classes}function ia(a){a.g&&w(a.f,[a.a.c("wf","loading")]);L(a,"loading")}function M(a){if(a.g){var b=y(a.f,a.a.c("wf","active")),c=[],d=[a.a.c("wf","loading")];b||c.push(a.a.c("wf","inactive"));w(a.f,c,d)}L(a,"inactive")}function L(a,b,c){if(a.j&&a.h[b])if(c)a.h[b](c.c,K(c));else a.h[b]()};function ja(){this.c={}}function ka(a,b,c){var d=[],e;for(e in b)if(b.hasOwnProperty(e)){var f=a.c[e];f&&d.push(f(b[e],c))}return d};function N(a,b){this.c=a;this.f=b;this.a=t(this.c,"span",{"aria-hidden":"true"},this.f)}function O(a){u(a.c,"body",a.a)}function P(a){return"display:block;position:absolute;top:-9999px;left:-9999px;font-size:300px;width:auto;height:auto;line-height:normal;margin:0;padding:0;font-variant:normal;white-space:nowrap;font-family:"+J(a.c)+";"+("font-style:"+I(a)+";font-weight:"+(a.f+"00")+";")};function Q(a,b,c,d,e,f){this.g=a;this.j=b;this.a=d;this.c=c;this.f=e||3E3;this.h=f||void 0}Q.prototype.start=function(){var a=this.c.m.document,b=this,c=q(),d=new Promise(function(d,e){function k(){q()-c>=b.f?e():a.fonts.load(fa(b.a),b.h).then(function(a){1<=a.length?d():setTimeout(k,25)},function(){e()})}k()}),e=new Promise(function(a,d){setTimeout(d,b.f)});Promise.race([e,d]).then(function(){b.g(b.a)},function(){b.j(b.a)})};function R(a,b,c,d,e,f,g){this.v=a;this.B=b;this.c=c;this.a=d;this.s=g||"BESbswy";this.f={};this.w=e||3E3;this.u=f||null;this.o=this.j=this.h=this.g=null;this.g=new N(this.c,this.s);this.h=new N(this.c,this.s);this.j=new N(this.c,this.s);this.o=new N(this.c,this.s);a=new H(this.a.c+",serif",K(this.a));a=P(a);this.g.a.style.cssText=a;a=new H(this.a.c+",sans-serif",K(this.a));a=P(a);this.h.a.style.cssText=a;a=new H("serif",K(this.a));a=P(a);this.j.a.style.cssText=a;a=new H("sans-serif",K(this.a));a=
P(a);this.o.a.style.cssText=a;O(this.g);O(this.h);O(this.j);O(this.o)}var S={D:"serif",C:"sans-serif"},T=null;function U(){if(null===T){var a=/AppleWebKit\/([0-9]+)(?:\.([0-9]+))/.exec(window.navigator.userAgent);T=!!a&&(536>parseInt(a[1],10)||536===parseInt(a[1],10)&&11>=parseInt(a[2],10))}return T}R.prototype.start=function(){this.f.serif=this.j.a.offsetWidth;this.f["sans-serif"]=this.o.a.offsetWidth;this.A=q();la(this)};
function ma(a,b,c){for(var d in S)if(S.hasOwnProperty(d)&&b===a.f[S[d]]&&c===a.f[S[d]])return!0;return!1}function la(a){var b=a.g.a.offsetWidth,c=a.h.a.offsetWidth,d;(d=b===a.f.serif&&c===a.f["sans-serif"])||(d=U()&&ma(a,b,c));d?q()-a.A>=a.w?U()&&ma(a,b,c)&&(null===a.u||a.u.hasOwnProperty(a.a.c))?V(a,a.v):V(a,a.B):na(a):V(a,a.v)}function na(a){setTimeout(p(function(){la(this)},a),50)}function V(a,b){setTimeout(p(function(){v(this.g.a);v(this.h.a);v(this.j.a);v(this.o.a);b(this.a)},a),0)};function W(a,b,c){this.c=a;this.a=b;this.f=0;this.o=this.j=!1;this.s=c}var X=null;W.prototype.g=function(a){var b=this.a;b.g&&w(b.f,[b.a.c("wf",a.c,K(a).toString(),"active")],[b.a.c("wf",a.c,K(a).toString(),"loading"),b.a.c("wf",a.c,K(a).toString(),"inactive")]);L(b,"fontactive",a);this.o=!0;oa(this)};
W.prototype.h=function(a){var b=this.a;if(b.g){var c=y(b.f,b.a.c("wf",a.c,K(a).toString(),"active")),d=[],e=[b.a.c("wf",a.c,K(a).toString(),"loading")];c||d.push(b.a.c("wf",a.c,K(a).toString(),"inactive"));w(b.f,d,e)}L(b,"fontinactive",a);oa(this)};function oa(a){0==--a.f&&a.j&&(a.o?(a=a.a,a.g&&w(a.f,[a.a.c("wf","active")],[a.a.c("wf","loading"),a.a.c("wf","inactive")]),L(a,"active")):M(a.a))};function pa(a){this.j=a;this.a=new ja;this.h=0;this.f=this.g=!0}pa.prototype.load=function(a){this.c=new ca(this.j,a.context||this.j);this.g=!1!==a.events;this.f=!1!==a.classes;qa(this,new ha(this.c,a),a)};
function ra(a,b,c,d,e){var f=0==--a.h;(a.f||a.g)&&setTimeout(function(){var a=e||null,k=d||null||{};if(0===c.length&&f)M(b.a);else{b.f+=c.length;f&&(b.j=f);var h,m=[];for(h=0;h<c.length;h++){var l=c[h],n=k[l.c],r=b.a,x=l;r.g&&w(r.f,[r.a.c("wf",x.c,K(x).toString(),"loading")]);L(r,"fontloading",x);r=null;null===X&&(X=window.FontFace?(x=/Gecko.*Firefox\/(\d+)/.exec(window.navigator.userAgent))?42<parseInt(x[1],10):!0:!1);X?r=new Q(p(b.g,b),p(b.h,b),b.c,l,b.s,n):r=new R(p(b.g,b),p(b.h,b),b.c,l,b.s,a,
n);m.push(r)}for(h=0;h<m.length;h++)m[h].start()}},0)}function qa(a,b,c){var d=[],e=c.timeout;ia(b);var d=ka(a.a,c,a.c),f=new W(a.c,b,e);a.h=d.length;b=0;for(c=d.length;b<c;b++)d[b].load(function(b,d,c){ra(a,f,b,d,c)})};function sa(a,b){this.c=a;this.a=b}function ta(a,b,c){var d=z(a.c);a=(a.a.api||"fast.fonts.net/jsapi").replace(/^.*http(s?):(\/\/)?/,"");return d+"//"+a+"/"+b+".js"+(c?"?v="+c:"")}
sa.prototype.load=function(a){function b(){if(f["__mti_fntLst"+d]){var c=f["__mti_fntLst"+d](),e=[],h;if(c)for(var m=0;m<c.length;m++){var l=c[m].fontfamily;void 0!=c[m].fontStyle&&void 0!=c[m].fontWeight?(h=c[m].fontStyle+c[m].fontWeight,e.push(new H(l,h))):e.push(new H(l))}a(e)}else setTimeout(function(){b()},50)}var c=this,d=c.a.projectId,e=c.a.version;if(d){var f=c.c.m;B(this.c,ta(c,d,e),function(e){e?a([]):(f["__MonotypeConfiguration__"+d]=function(){return c.a},b())}).id="__MonotypeAPIScript__"+
d}else a([])};function ua(a,b){this.c=a;this.a=b}ua.prototype.load=function(a){var b,c,d=this.a.urls||[],e=this.a.families||[],f=this.a.testStrings||{},g=new C;b=0;for(c=d.length;b<c;b++)A(this.c,d[b],D(g));var k=[];b=0;for(c=e.length;b<c;b++)if(d=e[b].split(":"),d[1])for(var h=d[1].split(","),m=0;m<h.length;m+=1)k.push(new H(d[0],h[m]));else k.push(new H(d[0]));F(g,function(){a(k,f)})};function va(a,b,c){a?this.c=a:this.c=b+wa;this.a=[];this.f=[];this.g=c||""}var wa="//fonts.googleapis.com/css";function xa(a,b){for(var c=b.length,d=0;d<c;d++){var e=b[d].split(":");3==e.length&&a.f.push(e.pop());var f="";2==e.length&&""!=e[1]&&(f=":");a.a.push(e.join(f))}}
function ya(a){if(0==a.a.length)throw Error("No fonts to load!");if(-1!=a.c.indexOf("kit="))return a.c;for(var b=a.a.length,c=[],d=0;d<b;d++)c.push(a.a[d].replace(/ /g,"+"));b=a.c+"?family="+c.join("%7C");0<a.f.length&&(b+="&subset="+a.f.join(","));0<a.g.length&&(b+="&text="+encodeURIComponent(a.g));return b};function za(a){this.f=a;this.a=[];this.c={}}
var Aa={latin:"BESbswy","latin-ext":"\u00e7\u00f6\u00fc\u011f\u015f",cyrillic:"\u0439\u044f\u0416",greek:"\u03b1\u03b2\u03a3",khmer:"\u1780\u1781\u1782",Hanuman:"\u1780\u1781\u1782"},Ba={thin:"1",extralight:"2","extra-light":"2",ultralight:"2","ultra-light":"2",light:"3",regular:"4",book:"4",medium:"5","semi-bold":"6",semibold:"6","demi-bold":"6",demibold:"6",bold:"7","extra-bold":"8",extrabold:"8","ultra-bold":"8",ultrabold:"8",black:"9",heavy:"9",l:"3",r:"4",b:"7"},Ca={i:"i",italic:"i",n:"n",normal:"n"},
Da=/^(thin|(?:(?:extra|ultra)-?)?light|regular|book|medium|(?:(?:semi|demi|extra|ultra)-?)?bold|black|heavy|l|r|b|[1-9]00)?(n|i|normal|italic)?$/;
function Ea(a){for(var b=a.f.length,c=0;c<b;c++){var d=a.f[c].split(":"),e=d[0].replace(/\+/g," "),f=["n4"];if(2<=d.length){var g;var k=d[1];g=[];if(k)for(var k=k.split(","),h=k.length,m=0;m<h;m++){var l;l=k[m];if(l.match(/^[\w-]+$/)){var n=Da.exec(l.toLowerCase());if(null==n)l="";else{l=n[2];l=null==l||""==l?"n":Ca[l];n=n[1];if(null==n||""==n)n="4";else var r=Ba[n],n=r?r:isNaN(n)?"4":n.substr(0,1);l=[l,n].join("")}}else l="";l&&g.push(l)}0<g.length&&(f=g);3==d.length&&(d=d[2],g=[],d=d?d.split(","):
g,0<d.length&&(d=Aa[d[0]])&&(a.c[e]=d))}a.c[e]||(d=Aa[e])&&(a.c[e]=d);for(d=0;d<f.length;d+=1)a.a.push(new H(e,f[d]))}};function Fa(a,b){this.c=a;this.a=b}var Ga={Arimo:!0,Cousine:!0,Tinos:!0};Fa.prototype.load=function(a){var b=new C,c=this.c,d=new va(this.a.api,z(c),this.a.text),e=this.a.families;xa(d,e);var f=new za(e);Ea(f);A(c,ya(d),D(b));F(b,function(){a(f.a,f.c,Ga)})};function Ha(a,b){this.c=a;this.a=b}Ha.prototype.load=function(a){var b=this.a.id,c=this.c.m;b?B(this.c,(this.a.api||"https://use.typekit.net")+"/"+b+".js",function(b){if(b)a([]);else if(c.Typekit&&c.Typekit.config&&c.Typekit.config.fn){b=c.Typekit.config.fn;for(var e=[],f=0;f<b.length;f+=2)for(var g=b[f],k=b[f+1],h=0;h<k.length;h++)e.push(new H(g,k[h]));try{c.Typekit.load({events:!1,classes:!1,async:!0})}catch(m){}a(e)}},2E3):a([])};function Ia(a,b){this.c=a;this.f=b;this.a=[]}Ia.prototype.load=function(a){var b=this.f.id,c=this.c.m,d=this;b?(c.__webfontfontdeckmodule__||(c.__webfontfontdeckmodule__={}),c.__webfontfontdeckmodule__[b]=function(b,c){for(var g=0,k=c.fonts.length;g<k;++g){var h=c.fonts[g];d.a.push(new H(h.name,ga("font-weight:"+h.weight+";font-style:"+h.style)))}a(d.a)},B(this.c,z(this.c)+(this.f.api||"//f.fontdeck.com/s/css/js/")+ea(this.c)+"/"+b+".js",function(b){b&&a([])})):a([])};var Y=new pa(window);Y.a.c.custom=function(a,b){return new ua(b,a)};Y.a.c.fontdeck=function(a,b){return new Ia(b,a)};Y.a.c.monotype=function(a,b){return new sa(b,a)};Y.a.c.typekit=function(a,b){return new Ha(b,a)};Y.a.c.google=function(a,b){return new Fa(b,a)};var Z={load:p(Y.load,Y)};"function"===typeof define&&define.amd?define(function(){return Z}):"undefined"!==typeof module&&module.exports?module.exports=Z:(window.WebFont=Z,window.WebFontConfig&&Y.load(window.WebFontConfig));}());

(function() {
    /**
    * Copyright 2004-present Facebook. All Rights Reserved.
    *
    * @providesModule UserAgent_DEPRECATED
    */

   /**
    *  Provides entirely client-side User Agent and OS detection. You should prefer
    *  the non-deprecated UserAgent module when possible, which exposes our
    *  authoritative server-side PHP-based detection to the client.
    *
    *  Usage is straightforward:
    *
    *    if (UserAgent_DEPRECATED.ie()) {
    *      //  IE
    *    }
    *
    *  You can also do version checks:
    *
    *    if (UserAgent_DEPRECATED.ie() >= 7) {
    *      //  IE7 or better
    *    }
    *
    *  The browser functions will return NaN if the browser does not match, so
    *  you can also do version compares the other way:
    *
    *    if (UserAgent_DEPRECATED.ie() < 7) {
    *      //  IE6 or worse
    *    }
    *
    *  Note that the version is a float and may include a minor version number,
    *  so you should always use range operators to perform comparisons, not
    *  strict equality.
    *
    *  **Note:** You should **strongly** prefer capability detection to browser
    *  version detection where it's reasonable:
    *
    *    http://www.quirksmode.org/js/support.html
    *
    *  Further, we have a large number of mature wrapper functions and classes
    *  which abstract away many browser irregularities. Check the documentation,
    *  grep for things, or ask on javascript@lists.facebook.com before writing yet
    *  another copy of "event || window.event".
    *
    */

   var _populated = false;

   // Browsers
   var _ie, _firefox, _opera, _webkit, _chrome;

   // Actual IE browser for compatibility mode
   var _ie_real_version;

   // Platforms
   var _osx, _windows, _linux, _android;

   // Architectures
   var _win64;

   // Devices
   var _iphone, _ipad, _native;

   var _mobile;

   function _populate() {
     if (_populated) {
       return;
     }

     _populated = true;

     // To work around buggy JS libraries that can't handle multi-digit
     // version numbers, Opera 10's user agent string claims it's Opera
     // 9, then later includes a Version/X.Y field:
     //
     // Opera/9.80 (foo) Presto/2.2.15 Version/10.10
     var uas = navigator.userAgent;
     var agent = /(?:MSIE.(\d+\.\d+))|(?:(?:Firefox|GranParadiso|Iceweasel).(\d+\.\d+))|(?:Opera(?:.+Version.|.)(\d+\.\d+))|(?:AppleWebKit.(\d+(?:\.\d+)?))|(?:Trident\/\d+\.\d+.*rv:(\d+\.\d+))/.exec(uas);
     var os    = /(Mac OS X)|(Windows)|(Linux)/.exec(uas);

     _iphone = /\b(iPhone|iP[ao]d)/.exec(uas);
     _ipad = /\b(iP[ao]d)/.exec(uas);
     _android = /Android/i.exec(uas);
     _native = /FBAN\/\w+;/i.exec(uas);
     _mobile = /Mobile/i.exec(uas);

     // Note that the IE team blog would have you believe you should be checking
     // for 'Win64; x64'.  But MSDN then reveals that you can actually be coming
     // from either x64 or ia64;  so ultimately, you should just check for Win64
     // as in indicator of whether you're in 64-bit IE.  32-bit IE on 64-bit
     // Windows will send 'WOW64' instead.
     _win64 = !!(/Win64/.exec(uas));

     if (agent) {
       _ie = agent[1] ? parseFloat(agent[1]) : (
             agent[5] ? parseFloat(agent[5]) : NaN);
       // IE compatibility mode
       if (_ie && document && document.documentMode) {
         _ie = document.documentMode;
       }
       // grab the "true" ie version from the trident token if available
       var trident = /(?:Trident\/(\d+.\d+))/.exec(uas);
       _ie_real_version = trident ? parseFloat(trident[1]) + 4 : _ie;

       _firefox = agent[2] ? parseFloat(agent[2]) : NaN;
       _opera   = agent[3] ? parseFloat(agent[3]) : NaN;
       _webkit  = agent[4] ? parseFloat(agent[4]) : NaN;
       if (_webkit) {
         // We do not add the regexp to the above test, because it will always
         // match 'safari' only since 'AppleWebKit' appears before 'Chrome' in
         // the userAgent string.
         agent = /(?:Chrome\/(\d+\.\d+))/.exec(uas);
         _chrome = agent && agent[1] ? parseFloat(agent[1]) : NaN;
       } else {
         _chrome = NaN;
       }
     } else {
       _ie = _firefox = _opera = _chrome = _webkit = NaN;
     }

     if (os) {
       if (os[1]) {
         // Detect OS X version.  If no version number matches, set _osx to true.
         // Version examples:  10, 10_6_1, 10.7
         // Parses version number as a float, taking only first two sets of
         // digits.  If only one set of digits is found, returns just the major
         // version number.
         var ver = /(?:Mac OS X (\d+(?:[._]\d+)?))/.exec(uas);

         _osx = ver ? parseFloat(ver[1].replace('_', '.')) : true;
       } else {
         _osx = false;
       }
       _windows = !!os[2];
       _linux   = !!os[3];
     } else {
       _osx = _windows = _linux = false;
     }
   }

   window.UserAgent_DEPRECATED = {

     /**
      *  Check if the UA is Internet Explorer.
      *
      *
      *  @return float|NaN Version number (if match) or NaN.
      */
     ie: function() {
       return _populate() || _ie;
     },

     /**
      * Check if we're in Internet Explorer compatibility mode.
      *
      * @return bool true if in compatibility mode, false if
      * not compatibility mode or not ie
      */
     ieCompatibilityMode: function() {
       return _populate() || (_ie_real_version > _ie);
     },


     /**
      * Whether the browser is 64-bit IE.  Really, this is kind of weak sauce;  we
      * only need this because Skype can't handle 64-bit IE yet.  We need to remove
      * this when we don't need it -- tracked by #601957.
      */
     ie64: function() {
       return UserAgent_DEPRECATED.ie() && _win64;
     },

     /**
      *  Check if the UA is Firefox.
      *
      *
      *  @return float|NaN Version number (if match) or NaN.
      */
     firefox: function() {
       return _populate() || _firefox;
     },


     /**
      *  Check if the UA is Opera.
      *
      *
      *  @return float|NaN Version number (if match) or NaN.
      */
     opera: function() {
       return _populate() || _opera;
     },


     /**
      *  Check if the UA is WebKit.
      *
      *
      *  @return float|NaN Version number (if match) or NaN.
      */
     webkit: function() {
       return _populate() || _webkit;
     },

     /**
      *  For Push
      *  WILL BE REMOVED VERY SOON. Use UserAgent_DEPRECATED.webkit
      */
     safari: function() {
       return UserAgent_DEPRECATED.webkit();
     },

     /**
      *  Check if the UA is a Chrome browser.
      *
      *
      *  @return float|NaN Version number (if match) or NaN.
      */
     chrome : function() {
       return _populate() || _chrome;
     },


     /**
      *  Check if the user is running Windows.
      *
      *  @return bool `true' if the user's OS is Windows.
      */
     windows: function() {
       return _populate() || _windows;
     },


     /**
      *  Check if the user is running Mac OS X.
      *
      *  @return float|bool   Returns a float if a version number is detected,
      *                       otherwise true/false.
      */
     osx: function() {
       return _populate() || _osx;
     },

     /**
      * Check if the user is running Linux.
      *
      * @return bool `true' if the user's OS is some flavor of Linux.
      */
     linux: function() {
       return _populate() || _linux;
     },

     /**
      * Check if the user is running on an iPhone or iPod platform.
      *
      * @return bool `true' if the user is running some flavor of the
      *    iPhone OS.
      */
     iphone: function() {
       return _populate() || _iphone;
     },

     mobile: function() {
       return _populate() || (_iphone || _ipad || _android || _mobile);
     },

     nativeApp: function() {
       // webviews inside of the native apps
       return _populate() || _native;
     },

     android: function() {
       return _populate() || _android;
     },

     ipad: function() {
       return _populate() || _ipad;
     }
   };
    
})();

(function(){
    //Mouse wheel events are inconsistent across browsers
    // This function will normalize it
    //https://github.com/facebookarchive/fixed-data-table/blob/master/src/vendor_upstream/dom/normalizeWheel.js
    /**
     * Check if an event is supported.
     * Ref: http://perfectionkills.com/detecting-event-support-without-browser-sniffing/
     */
    function isEventSupported(event) {
      var testEl = document.createElement('div');
      var isSupported;

      event = 'on' + event;
      isSupported = (event in testEl);

      if (!isSupported) {
        testEl.setAttribute(event, 'return;');
        isSupported = typeof testEl[event] === 'function';
      }
      testEl = null;

      return isSupported;
    }
    // Reasonable defaults
    var PIXEL_STEP  = 10;
    var LINE_HEIGHT = 40;
    var PAGE_HEIGHT = 800;
    function normalizeWheel(/*object*/ event) /*object*/ {
      var sX = 0, sY = 0,       // spinX, spinY
          pX = 0, pY = 0;       // pixelX, pixelY

      // Legacy
      if ('detail'      in event) { sY = event.detail; }
      if ('wheelDelta'  in event) { sY = -event.wheelDelta / 120; }
      if ('wheelDeltaY' in event) { sY = -event.wheelDeltaY / 120; }
      if ('wheelDeltaX' in event) { sX = -event.wheelDeltaX / 120; }

      // side scrolling on FF with DOMMouseScroll
      if ( 'axis' in event && event.axis === event.HORIZONTAL_AXIS ) {
        sX = sY;
        sY = 0;
      }

      pX = sX * PIXEL_STEP;
      pY = sY * PIXEL_STEP;

      if ('deltaY' in event) { pY = event.deltaY; }
      if ('deltaX' in event) { pX = event.deltaX; }

      if ((pX || pY) && event.deltaMode) {
        if (event.deltaMode == 1) {          // delta in LINE units
          pX *= LINE_HEIGHT;
          pY *= LINE_HEIGHT;
        } else {                             // delta in PAGE units
          pX *= PAGE_HEIGHT;
          pY *= PAGE_HEIGHT;
        }
      }

      // Fall-back if spin cannot be determined
      if (pX && !sX) { sX = (pX < 1) ? -1 : 1; }
      if (pY && !sY) { sY = (pY < 1) ? -1 : 1; }

      return { spinX  : sX,
               spinY  : sY,
               pixelX : pX,
               pixelY : pY };
    }

    normalizeWheel.getEventType = function() /*string*/ {
      return (UserAgent_DEPRECATED.firefox())
               ? 'DOMMouseScroll'
               : (isEventSupported('wheel'))
                   ? 'wheel'
                   : 'mousewheel';
    };
    
    window.cn1NormalizeWheel = normalizeWheel;
})();

window.copyWheelEvent = function(event, iframe, x, y) {
    var type = event.type == 'MozMousePixelScroll' ? 'DOMMouseScroll' : event.type;
    var evt = new CustomEvent(type, {bubbles: true, cancelable: true});
    evt.clientX = event.clientX + x;
    evt.clientY = event.clientY + y;
    if ('axis' in event) evt.axis = event.axis;
    evt.cn1Detail = event.detail;
    if ('deltaY' in event) evt.deltaY = event.deltaY;
    if ('deltaX' in event) evt.deltaX = event.deltaX;
    if ('wheelDelta' in event) evt.wheelDelta = event.wheelDelta;
    if ('wheelDeltaX' in event) evt.wheelDeltaX = event.wheelDeltaX;
    if ('wheelDeltaY' in event) {
        evt.wheelDeltaY = event.wheelDeltaY;   
    } else if ('detail' in event) {
        // Firefox.. we can't set detail, so we need to fake the wheelDeltaY
        // so that the normalizeWheel method will work
        evt.wheelDeltaY = - event.detail * 120;
    }
    //console.log('wheel event', event, evt, event.wheelDeltaY);
    return evt;
};

window.copyTouchEvent = function(event, iframe, x, y) {
    //console.log("Copying touch event" + event);
    var evt = new CustomEvent(event.type, {bubbles: true, cancelable: true});
    if ('clientX' in event) evt.clientX = event.clientX + x;
    if ('clientY' in event) evt.clientY = event.clientY + y;
    if ('changedTouches' in event) {
        var touches = [];
        for (var i=0; i<event.changedTouches.length; i++) {
            touches.push({clientX: event.changedTouches[i].clientX+x, clientY: event.changedTouches[i].clientY+y});
        }
        evt.touches = touches;
    } else if ('touches' in event) {
        var touches = [];
        for (var i=0; i<event.touches.length; i++) {
            touches.push({clientX: event.touches[i].clientX+x, clientY: event.touches[i].clientY+y});
        }
        evt.touches = touches;
    }
    evt.targetTouches = evt.touches;

    return evt;
};

window.copyMouseEvent = function(event, srcEl) {
    var rect = srcEl.getBoundingClientRect();
    var x = rect.left;
    var y = rect.top;
    var evt = new CustomEvent(event.type, {bubbles: true, cancelable: true});
    if ('clientX' in event) evt.clientX = event.clientX + x;
    if ('clientY' in event) evt.clientY = event.clientY + y;
    return evt;
};

window.console = window.console || {
  log: function () {}
};

window.cn1GlobalWeakMap = (window.WeakMap === undefined) ? null : new WeakMap();
window.cn1_native_interfaces = {};
window.cn1_get_native_interfaces = function() {
  return window.cn1_native_interfaces;  
};

window.cn1CreateByteArray = function(arr) {
    return arr;
};

window.cn1CreateIntArray = function(arr) {
    return arr;
};

window.cn1CreateShortArray = function(arr) {
    return arr;
};

window.cn1CreateFloatArray = function(arr) {
    return arr;
};

window.cn1CreateDoubleArray = function(arr) {
    return arr;
}

window.cn1CreateLongArray = function(arr) {
    if (arr === null) {
        return null;
    }
    var len = arr.length;
    var out = $rt_createLongArray(len);
    var data = out.data;
    for (var i=0; i<len; i++) {
        data[i] = Long_fromInt(arr[i]);
    }
    return out;
};

window.cn1WrapBooleanArray = function(arr) {
    return arr;
};


// Matrix stuff
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

// Copyright 2008 Google Inc. All Rights Reserved.


/**
 * @fileoverview Provides an object representation of an AffineTransform and
 * methods for working with it.
 */


//goog.provide('goog.graphics.AffineTransform');
window.goog = window.goog || {};
window.goog.graphics = window.goog.graphics || {};

/**
 * Creates a 2D affine transform. An affine transform performs a linear
 * mapping from 2D coordinates to other 2D coordinates that preserves the
 * "straightness" and "parallelness" of lines.
 *
 * Such a coordinate transformation can be represented by a 3 row by 3 column
 * matrix with an implied last row of [ 0 0 1 ]. This matrix transforms source
 * coordinates (x,y) into destination coordinates (x',y') by considering them
 * to be a column vector and multiplying the coordinate vector by the matrix
 * according to the following process:
 * <pre>
 *      [ x']   [  m00  m01  m02  ] [ x ]   [ m00x + m01y + m02 ]
 *      [ y'] = [  m10  m11  m12  ] [ y ] = [ m10x + m11y + m12 ]
 *      [ 1 ]   [   0    0    1   ] [ 1 ]   [         1         ]
 * </pre>
 *
 * This class is optimized for speed and minimizes calculations based on its
 * knowledge of the underlying matrix (as opposed to say simply performing
 * matrix multiplication).
 *
 * @param {number} opt_m00 The m00 coordinate of the transform.
 * @param {number} opt_m10 The m10 coordinate of the transform.
 * @param {number} opt_m01 The m01 coordinate of the transform.
 * @param {number} opt_m11 The m11 coordinate of the transform.
 * @param {number} opt_m02 The m02 coordinate of the transform.
 * @param {number} opt_m12 The m12 coordinate of the transform.
 * @constructor
 */
goog.graphics.AffineTransform = function(opt_m00, opt_m10, opt_m01,
    opt_m11, opt_m02, opt_m12) {
  if (arguments.length == 6) {
    this.setTransform(/** @type {number} */ (opt_m00),
                      /** @type {number} */ (opt_m10),
                      /** @type {number} */ (opt_m01),
                      /** @type {number} */ (opt_m11),
                      /** @type {number} */ (opt_m02),
                      /** @type {number} */ (opt_m12));
  } else if (arguments.length != 0) {
    throw Error('Insufficient matrix parameters');
  } else {
    this.m00_ = this.m11_ = 1;
    this.m10_ = this.m01_ = this.m02_ = this.m12_ = 0;
  }
};


/**
 * @return {boolean} Whether this transform is the identity transform.
 */
goog.graphics.AffineTransform.prototype.isIdentity = function() {
  return this.m00_ == 1 && this.m10_ == 0 && this.m01_ == 0 &&
      this.m11_ == 1 && this.m02_ == 0 && this.m12_ == 0;
};


/**
 * @return {!goog.graphics.AffineTransform} A copy of this transform.
 */
goog.graphics.AffineTransform.prototype.cloneTransform = function() {
  return new goog.graphics.AffineTransform(this.m00_, this.m10_, this.m01_,
      this.m11_, this.m02_, this.m12_);
};


/**
 * Sets this transform to the matrix specified by the 6 values.
 *
 * @param {number} m00 The m00 coordinate of the transform.
 * @param {number} m10 The m10 coordinate of the transform.
 * @param {number} m01 The m01 coordinate of the transform.
 * @param {number} m11 The m11 coordinate of the transform.
 * @param {number} m02 The m02 coordinate of the transform.
 * @param {number} m12 The m12 coordinate of the transform.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.setTransform = function(m00, m10, m01,
    m11, m02, m12) {
  //if (!goog.isNumber(m00) || !goog.isNumber(m10) || !goog.isNumber(m01) ||
  //    !goog.isNumber(m11) || !goog.isNumber(m02) || !goog.isNumber(m12)) {
  //  throw Error('Invalid transform parameters');
  //}
  this.m00_ = m00;
  this.m10_ = m10;
  this.m01_ = m01;
  this.m11_ = m11;
  this.m02_ = m02;
  this.m12_ = m12;
  return this;
};


/**
 * Sets this transform to be identical to the given transform.
 *
 * @param {!goog.graphics.AffineTransform} tx The transform to copy.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.copyFrom = function(tx) {
  this.m00_ = tx.m00_;
  this.m10_ = tx.m10_;
  this.m01_ = tx.m01_;
  this.m11_ = tx.m11_;
  this.m02_ = tx.m02_;
  this.m12_ = tx.m12_;
  return this;
};


/**
 * Concatentates this transform with a scaling transformation.
 *
 * @param {number} sx The x-axis scaling factor.
 * @param {number} sy The y-axis scaling factor.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.scale = function(sx, sy) {
  this.m00_ *= sx;
  this.m10_ *= sx;
  this.m01_ *= sy;
  this.m11_ *= sy;
  return this;
};


/**
 * Concatentates this transform with a translate transformation.
 *
 * @param {number} dx The distance to translate in the x direction.
 * @param {number} dy The distance to translate in the y direction.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.translate = function(dx, dy) {
  this.m02_ += dx * this.m00_ + dy * this.m01_;
  this.m12_ += dx * this.m10_ + dy * this.m11_;
  return this;
};


/**
 * Concatentates this transform with a rotation transformation around an anchor
 * point.
 *
 * @param {number} theta The angle of rotation measured in radians.
 * @param {number} x The x coordinate of the anchor point.
 * @param {number} y The y coordinate of the anchor point.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.rotate = function(theta, x, y) {
  return this.concatenate(
      goog.graphics.AffineTransform.getRotateInstance(theta, x, y));
};


/**
 * Concatentates this transform with a shear transformation.
 *
 * @param {number} shx The x shear factor.
 * @param {number} shy The y shear factor.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.shear = function(shx, shy) {
  var m00 = this.m00_;
  var m10 = this.m10_;
  this.m00_ += shy * this.m01_;
  this.m10_ += shy * this.m11_;
  this.m01_ += shx * m00;
  this.m11_ += shx * m10;
  return this;
};


/**
 * @return {string} A string representation of this transform. The format of
 *     of the string is compatible with SVG matrix notation, i.e.
 *     "matrix(a,b,c,d,e,f)".
 */
goog.graphics.AffineTransform.prototype.toString = function() {
  return 'matrix(' + [this.m00_, this.m10_, this.m01_, this.m11_,
      this.m02_, this.m12_].join(',') + ')';
};


/**
 * @return {number} The scaling factor in the x-direction (m00).
 */
goog.graphics.AffineTransform.prototype.getScaleX = function() {
  return this.m00_;
};


/**
 * @return {number} The scaling factor in the y-direction (m11).
 */
goog.graphics.AffineTransform.prototype.getScaleY = function() {
  return this.m11_;
};


/**
 * @return {number} The translation in the x-direction (m02).
 */
goog.graphics.AffineTransform.prototype.getTranslateX = function() {
  return this.m02_;
};


/**
 * @return {number} The translation in the y-direction (m12).
 */
goog.graphics.AffineTransform.prototype.getTranslateY = function() {
  return this.m12_;
};


/**
 * @return {number} The shear factor in the x-direction (m01).
 */
goog.graphics.AffineTransform.prototype.getShearX = function() {
  return this.m01_;
};


/**
 * @return {number} The shear factor in the y-direction (m10).
 */
goog.graphics.AffineTransform.prototype.getShearY = function() {
  return this.m10_;
};


/**
 * Concatenates an affine transform to this transform.
 *
 * @param {!goog.graphics.AffineTransform} tx The transform to concatenate.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.concatenate = function(tx) {
  var m0 = this.m00_;
  var m1 = this.m01_;
  this.m00_ = tx.m00_ * m0 + tx.m10_ * m1;
  this.m01_ = tx.m01_ * m0 + tx.m11_ * m1;
  this.m02_ += tx.m02_ * m0 + tx.m12_ * m1;

  m0 = this.m10_;
  m1 = this.m11_;
  this.m10_ = tx.m00_ * m0 + tx.m10_ * m1;
  this.m11_ = tx.m01_ * m0 + tx.m11_ * m1;
  this.m12_ += tx.m02_ * m0 + tx.m12_ * m1;
  return this;
};


/**
 * Pre-concatenates an affine transform to this transform.
 *
 * @param {!goog.graphics.AffineTransform} tx The transform to preconcatenate.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.preConcatenate = function(tx) {
  var m0 = this.m00_;
  var m1 = this.m10_;
  this.m00_ = tx.m00_ * m0 + tx.m01_ * m1;
  this.m10_ = tx.m10_ * m0 + tx.m11_ * m1;

  m0 = this.m01_;
  m1 = this.m11_;
  this.m01_ = tx.m00_ * m0 + tx.m01_ * m1;
  this.m11_ = tx.m10_ * m0 + tx.m11_ * m1;

  m0 = this.m02_;
  m1 = this.m12_;
  this.m02_ = tx.m00_ * m0 + tx.m01_ * m1 + tx.m02_;
  this.m12_ = tx.m10_ * m0 + tx.m11_ * m1 + tx.m12_;
  return this;
};


/**
 * Transforms an array of coordinates by this transform and stores the result
 * into a destination array.
 *
 * @param {!Array.<number>} src The array containing the source points
 *     as x, y value pairs.
 * @param {number} srcOff The offset to the first point to be transformed.
 * @param {!Array.<number>} dst The array into which to store the transformed
 *     point pairs.
 * @param {number} dstOff The offset of the location of the first transformed
 *     point in the destination array.
 * @param {number} numPts The number of points to tranform.
 */
goog.graphics.AffineTransform.prototype.transform = function(src, srcOff, dst,
    dstOff, numPts) {
  var i = srcOff;
  var j = dstOff;
  var srcEnd = srcOff + 2 * numPts;
  while (i < srcEnd) {
    var x = src[i++];
    var y = src[i++];
    dst[j++] = x * this.m00_ + y * this.m01_ + this.m02_;
    dst[j++] = x * this.m10_ + y * this.m11_ + this.m12_;
  }
};


/**
 * @return {number} The determinant of this transform.
 */
goog.graphics.AffineTransform.prototype.getDeterminant = function() {
  return this.m00_ * this.m11_ - this.m01_ * this.m10_;
};


/**
 * Returns whether the transform is invertible. A transform is not invertible
 * if the determinant is 0 or any value is non-finite or NaN.
 *
 * @return {boolean} Whether the transform is invertible.
 */
goog.graphics.AffineTransform.prototype.isInvertible = function() {
  var det = this.getDeterminant();
  return goog.math.isFiniteNumber(det) &&
      goog.math.isFiniteNumber(this.m02_) &&
      goog.math.isFiniteNumber(this.m12_) &&
      det != 0;
};


/**
 * @return {!goog.graphics.AffineTransform} An AffineTransform object
 *     representing the inverse transformation.
 */
goog.graphics.AffineTransform.prototype.createInverse = function() {
  var det = this.getDeterminant();
  return new goog.graphics.AffineTransform(
      this.m11_ / det,
      -this.m10_ / det,
      -this.m01_ / det,
      this.m00_ / det,
      (this.m01_ * this.m12_ - this.m11_ * this.m02_) / det,
      (this.m10_ * this.m02_ - this.m00_ * this.m12_) / det);
};


/**
 * Creates a transform representing a scaling transformation.
 *
 * @param {number} sx The x-axis scaling factor.
 * @param {number} sy The y-axis scaling factor.
 * @return {!goog.graphics.AffineTransform} A transform representing a scaling
 *     transformation.
 */
goog.graphics.AffineTransform.getScaleInstance = function(sx, sy) {
  return new goog.graphics.AffineTransform().setToScale(sx, sy);
};


/**
 * Creates a transform representing a translation transformation.
 *
 * @param {number} dx The distance to translate in the x direction.
 * @param {number} dy The distance to translate in the y direction.
 * @return {!goog.graphics.AffineTransform} A transform representing a
 *     translation transformation.
 */
goog.graphics.AffineTransform.getTranslateInstance = function(dx, dy) {
  return new goog.graphics.AffineTransform().setToTranslation(dx, dy);
};


/**
 * Creates a transform representing a shearing transformation.
 *
 * @param {number} shx The x-axis shear factor.
 * @param {number} shy The y-axis shear factor.
 * @return {!goog.graphics.AffineTransform} A transform representing a shearing
 *     transformation.
 */
goog.graphics.AffineTransform.getShearInstance = function(shx, shy) {
  return new goog.graphics.AffineTransform().setToShear(shx, shy);
};


/**
 * Creates a transform representing a rotation transformation.
 *
 * @param {number} theta The angle of rotation measured in radians.
 * @param {number} x The x coordinate of the anchor point.
 * @param {number} y The y coordinate of the anchor point.
 * @return {!goog.graphics.AffineTransform} A transform representing a rotation
 *     transformation.
 */
goog.graphics.AffineTransform.getRotateInstance = function(theta, x, y) {
  return new goog.graphics.AffineTransform().setToRotation(theta, x, y);
};


/**
 * Sets this transform to a scaling transformation.
 *
 * @param {number} sx The x-axis scaling factor.
 * @param {number} sy The y-axis scaling factor.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.setToScale = function(sx, sy) {
  return this.setTransform(sx, 0, 0, sy, 0, 0);
};


goog.graphics.AffineTransform.prototype.isEqualTo = function(t) {
  return this.m00_ === t.m00_ && 
          this.m01_ === t.m01_ && 
          this.m02_ === t.m02_ &&
          this.m10_ === t.m10_ &&
          this.m11_ === t.m11_ &&
          this.m12_ === t.m12_;
          
  
};

/**
 * Sets this transform to a translation transformation.
 *
 * @param {number} dx The distance to translate in the x direction.
 * @param {number} dy The distance to translate in the y direction.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.setToTranslation = function(dx, dy) {
  return this.setTransform(1, 0, 0, 1, dx, dy);
};


/**
 * Sets this transform to a shearing transformation.
 *
 * @param {number} shx The x-axis shear factor.
 * @param {number} shy The y-axis shear factor.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.setToShear = function(shx, shy) {
  return this.setTransform(1, shy, shx, 1, 0, 0);
};


/**
 * Sets this transform to a rotation transformation.
 *
 * @param {number} theta The angle of rotation measured in radians.
 * @param {number} x The x coordinate of the anchor point.
 * @param {number} y The y coordinate of the anchor point.
 * @return {!goog.graphics.AffineTransform} This affine transform.
 */
goog.graphics.AffineTransform.prototype.setToRotation = function(theta, x, y) {
  var cos = Math.cos(theta);
  var sin = Math.sin(theta);
  return this.setTransform(cos, sin, -sin, cos,
      x - x * cos + y * sin, y - x * sin - y * cos);
};

// END MATRIX STUFF

/*!
    localForage -- Offline Storage, Improved
    Version 1.4.3
    https://mozilla.github.io/localForage
    (c) 2013-2016 Mozilla, Apache License 2.0
*/
!function(a){if("object"==typeof exports&&"undefined"!=typeof module)module.exports=a();else if("function"==typeof define&&define.amd)define([],a);else{var b;b="undefined"!=typeof window?window:"undefined"!=typeof global?global:"undefined"!=typeof self?self:this,b.localforage=a()}}(function(){return function a(b,c,d){function e(g,h){if(!c[g]){if(!b[g]){var i="function"==typeof require&&require;if(!h&&i)return i(g,!0);if(f)return f(g,!0);var j=new Error("Cannot find module '"+g+"'");throw j.code="MODULE_NOT_FOUND",j}var k=c[g]={exports:{}};b[g][0].call(k.exports,function(a){var c=b[g][1][a];return e(c?c:a)},k,k.exports,a,b,c,d)}return c[g].exports}for(var f="function"==typeof require&&require,g=0;g<d.length;g++)e(d[g]);return e}({1:[function(a,b,c){(function(a){"use strict";function c(){k=!0;for(var a,b,c=l.length;c;){for(b=l,l=[],a=-1;++a<c;)b[a]();c=l.length}k=!1}function d(a){1!==l.push(a)||k||e()}var e,f=a.MutationObserver||a.WebKitMutationObserver;if(f){var g=0,h=new f(c),i=a.document.createTextNode("");h.observe(i,{characterData:!0}),e=function(){i.data=g=++g%2}}else if(a.setImmediate||"undefined"==typeof a.MessageChannel)e="document"in a&&"onreadystatechange"in a.document.createElement("script")?function(){var b=a.document.createElement("script");b.onreadystatechange=function(){c(),b.onreadystatechange=null,b.parentNode.removeChild(b),b=null},a.document.documentElement.appendChild(b)}:function(){setTimeout(c,0)};else{var j=new a.MessageChannel;j.port1.onmessage=c,e=function(){j.port2.postMessage(0)}}var k,l=[];b.exports=d}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{}],2:[function(a,b,c){"use strict";function d(){}function e(a){if("function"!=typeof a)throw new TypeError("resolver must be a function");this.state=s,this.queue=[],this.outcome=void 0,a!==d&&i(this,a)}function f(a,b,c){this.promise=a,"function"==typeof b&&(this.onFulfilled=b,this.callFulfilled=this.otherCallFulfilled),"function"==typeof c&&(this.onRejected=c,this.callRejected=this.otherCallRejected)}function g(a,b,c){o(function(){var d;try{d=b(c)}catch(e){return p.reject(a,e)}d===a?p.reject(a,new TypeError("Cannot resolve promise with itself")):p.resolve(a,d)})}function h(a){var b=a&&a.then;return a&&"object"==typeof a&&"function"==typeof b?function(){b.apply(a,arguments)}:void 0}function i(a,b){function c(b){f||(f=!0,p.reject(a,b))}function d(b){f||(f=!0,p.resolve(a,b))}function e(){b(d,c)}var f=!1,g=j(e);"error"===g.status&&c(g.value)}function j(a,b){var c={};try{c.value=a(b),c.status="success"}catch(d){c.status="error",c.value=d}return c}function k(a){return a instanceof this?a:p.resolve(new this(d),a)}function l(a){var b=new this(d);return p.reject(b,a)}function m(a){function b(a,b){function d(a){g[b]=a,++h!==e||f||(f=!0,p.resolve(j,g))}c.resolve(a).then(d,function(a){f||(f=!0,p.reject(j,a))})}var c=this;if("[object Array]"!==Object.prototype.toString.call(a))return this.reject(new TypeError("must be an array"));var e=a.length,f=!1;if(!e)return this.resolve([]);for(var g=new Array(e),h=0,i=-1,j=new this(d);++i<e;)b(a[i],i);return j}function n(a){function b(a){c.resolve(a).then(function(a){f||(f=!0,p.resolve(h,a))},function(a){f||(f=!0,p.reject(h,a))})}var c=this;if("[object Array]"!==Object.prototype.toString.call(a))return this.reject(new TypeError("must be an array"));var e=a.length,f=!1;if(!e)return this.resolve([]);for(var g=-1,h=new this(d);++g<e;)b(a[g]);return h}var o=a(1),p={},q=["REJECTED"],r=["FULFILLED"],s=["PENDING"];b.exports=c=e,e.prototype["catch"]=function(a){return this.then(null,a)},e.prototype.then=function(a,b){if("function"!=typeof a&&this.state===r||"function"!=typeof b&&this.state===q)return this;var c=new this.constructor(d);if(this.state!==s){var e=this.state===r?a:b;g(c,e,this.outcome)}else this.queue.push(new f(c,a,b));return c},f.prototype.callFulfilled=function(a){p.resolve(this.promise,a)},f.prototype.otherCallFulfilled=function(a){g(this.promise,this.onFulfilled,a)},f.prototype.callRejected=function(a){p.reject(this.promise,a)},f.prototype.otherCallRejected=function(a){g(this.promise,this.onRejected,a)},p.resolve=function(a,b){var c=j(h,b);if("error"===c.status)return p.reject(a,c.value);var d=c.value;if(d)i(a,d);else{a.state=r,a.outcome=b;for(var e=-1,f=a.queue.length;++e<f;)a.queue[e].callFulfilled(b)}return a},p.reject=function(a,b){a.state=q,a.outcome=b;for(var c=-1,d=a.queue.length;++c<d;)a.queue[c].callRejected(b);return a},c.resolve=k,c.reject=l,c.all=m,c.race=n},{1:1}],3:[function(a,b,c){(function(b){"use strict";"function"!=typeof b.Promise&&(b.Promise=a(2))}).call(this,"undefined"!=typeof global?global:"undefined"!=typeof self?self:"undefined"!=typeof window?window:{})},{2:2}],4:[function(a,b,c){"use strict";function d(a,b){if(!(a instanceof b))throw new TypeError("Cannot call a class as a function")}function e(){try{if("undefined"!=typeof indexedDB)return indexedDB;if("undefined"!=typeof webkitIndexedDB)return webkitIndexedDB;if("undefined"!=typeof mozIndexedDB)return mozIndexedDB;if("undefined"!=typeof OIndexedDB)return OIndexedDB;if("undefined"!=typeof msIndexedDB)return msIndexedDB}catch(a){}}function f(){try{return fa?"undefined"!=typeof openDatabase&&"undefined"!=typeof navigator&&navigator.userAgent&&/Safari/.test(navigator.userAgent)&&!/Chrome/.test(navigator.userAgent)?!1:fa&&"function"==typeof fa.open&&"undefined"!=typeof IDBKeyRange:!1}catch(a){return!1}}function g(){return"function"==typeof openDatabase}function h(){try{return"undefined"!=typeof localStorage&&"setItem"in localStorage&&localStorage.setItem}catch(a){return!1}}function i(a,b){a=a||[],b=b||{};try{return new Blob(a,b)}catch(c){if("TypeError"!==c.name)throw c;for(var d="undefined"!=typeof BlobBuilder?BlobBuilder:"undefined"!=typeof MSBlobBuilder?MSBlobBuilder:"undefined"!=typeof MozBlobBuilder?MozBlobBuilder:WebKitBlobBuilder,e=new d,f=0;f<a.length;f+=1)e.append(a[f]);return e.getBlob(b.type)}}function j(a,b){b&&a.then(function(a){b(null,a)},function(a){b(a)})}function k(a,b,c){"function"==typeof b&&a.then(b),"function"==typeof c&&a["catch"](c)}function l(a){for(var b=a.length,c=new ArrayBuffer(b),d=new Uint8Array(c),e=0;b>e;e++)d[e]=a.charCodeAt(e);return c}function m(a){return new ia(function(b){var c=i([""]);a.objectStore(ja).put(c,"key"),a.onabort=function(a){a.preventDefault(),a.stopPropagation(),b(!1)},a.oncomplete=function(){var a=navigator.userAgent.match(/Chrome\/(\d+)/),c=navigator.userAgent.match(/Edge\//);b(c||!a||parseInt(a[1],10)>=43)}})["catch"](function(){return!1})}function n(a){return"boolean"==typeof ga?ia.resolve(ga):m(a).then(function(a){return ga=a})}function o(a){var b=ha[a.name],c={};c.promise=new ia(function(a){c.resolve=a}),b.deferredOperations.push(c),b.dbReady?b.dbReady=b.dbReady.then(function(){return c.promise}):b.dbReady=c.promise}function p(a){var b=ha[a.name],c=b.deferredOperations.pop();c&&c.resolve()}function q(a,b){return new ia(function(c,d){if(a.db){if(!b)return c(a.db);o(a),a.db.close()}var e=[a.name];b&&e.push(a.version);var f=fa.open.apply(fa,e);b&&(f.onupgradeneeded=function(b){var c=f.result;try{c.createObjectStore(a.storeName),b.oldVersion<=1&&c.createObjectStore(ja)}catch(d){if("ConstraintError"!==d.name)throw d;console.warn('The database "'+a.name+'" has been upgraded from version '+b.oldVersion+" to version "+b.newVersion+', but the storage "'+a.storeName+'" already exists.')}}),f.onerror=function(){d(f.error)},f.onsuccess=function(){c(f.result),p(a)}})}function r(a){return q(a,!1)}function s(a){return q(a,!0)}function t(a,b){if(!a.db)return!0;var c=!a.db.objectStoreNames.contains(a.storeName),d=a.version<a.db.version,e=a.version>a.db.version;if(d&&(a.version!==b&&console.warn('The database "'+a.name+"\" can't be downgraded from version "+a.db.version+" to version "+a.version+"."),a.version=a.db.version),e||c){if(c){var f=a.db.version+1;f>a.version&&(a.version=f)}return!0}return!1}function u(a){return new ia(function(b,c){var d=new FileReader;d.onerror=c,d.onloadend=function(c){var d=btoa(c.target.result||"");b({__local_forage_encoded_blob:!0,data:d,type:a.type})},d.readAsBinaryString(a)})}function v(a){var b=l(atob(a.data));return i([b],{type:a.type})}function w(a){return a&&a.__local_forage_encoded_blob}function x(a){var b=this,c=b._initReady().then(function(){var a=ha[b._dbInfo.name];return a&&a.dbReady?a.dbReady:void 0});return k(c,a,a),c}function y(a){function b(){return ia.resolve()}var c=this,d={db:null};if(a)for(var e in a)d[e]=a[e];ha||(ha={});var f=ha[d.name];f||(f={forages:[],db:null,dbReady:null,deferredOperations:[]},ha[d.name]=f),f.forages.push(c),c._initReady||(c._initReady=c.ready,c.ready=x);for(var g=[],h=0;h<f.forages.length;h++){var i=f.forages[h];i!==c&&g.push(i._initReady()["catch"](b))}var j=f.forages.slice(0);return ia.all(g).then(function(){return d.db=f.db,r(d)}).then(function(a){return d.db=a,t(d,c._defaultConfig.version)?s(d):a}).then(function(a){d.db=f.db=a,c._dbInfo=d;for(var b=0;b<j.length;b++){var e=j[b];e!==c&&(e._dbInfo.db=d.db,e._dbInfo.version=d.version)}})}function z(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo,f=e.db.transaction(e.storeName,"readonly").objectStore(e.storeName),g=f.get(a);g.onsuccess=function(){var a=g.result;void 0===a&&(a=null),w(a)&&(a=v(a)),b(a)},g.onerror=function(){d(g.error)}})["catch"](d)});return j(d,b),d}function A(a,b){var c=this,d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo,f=e.db.transaction(e.storeName,"readonly").objectStore(e.storeName),g=f.openCursor(),h=1;g.onsuccess=function(){var c=g.result;if(c){var d=c.value;w(d)&&(d=v(d));var e=a(d,c.key,h++);void 0!==e?b(e):c["continue"]()}else b()},g.onerror=function(){d(g.error)}})["catch"](d)});return j(d,b),d}function B(a,b,c){var d=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var e=new ia(function(c,e){var f;d.ready().then(function(){return f=d._dbInfo,"[object Blob]"===ka.call(b)?n(f.db).then(function(a){return a?b:u(b)}):b}).then(function(b){var d=f.db.transaction(f.storeName,"readwrite"),g=d.objectStore(f.storeName);null===b&&(b=void 0),d.oncomplete=function(){void 0===b&&(b=null),c(b)},d.onabort=d.onerror=function(){var a=h.error?h.error:h.transaction.error;e(a)};var h=g.put(b,a)})["catch"](e)});return j(e,c),e}function C(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo,f=e.db.transaction(e.storeName,"readwrite"),g=f.objectStore(e.storeName),h=g["delete"](a);f.oncomplete=function(){b()},f.onerror=function(){d(h.error)},f.onabort=function(){var a=h.error?h.error:h.transaction.error;d(a)}})["catch"](d)});return j(d,b),d}function D(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo,e=d.db.transaction(d.storeName,"readwrite"),f=e.objectStore(d.storeName),g=f.clear();e.oncomplete=function(){a()},e.onabort=e.onerror=function(){var a=g.error?g.error:g.transaction.error;c(a)}})["catch"](c)});return j(c,a),c}function E(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo,e=d.db.transaction(d.storeName,"readonly").objectStore(d.storeName),f=e.count();f.onsuccess=function(){a(f.result)},f.onerror=function(){c(f.error)}})["catch"](c)});return j(c,a),c}function F(a,b){var c=this,d=new ia(function(b,d){return 0>a?void b(null):void c.ready().then(function(){var e=c._dbInfo,f=e.db.transaction(e.storeName,"readonly").objectStore(e.storeName),g=!1,h=f.openCursor();h.onsuccess=function(){var c=h.result;return c?void(0===a?b(c.key):g?b(c.key):(g=!0,c.advance(a))):void b(null)},h.onerror=function(){d(h.error)}})["catch"](d)});return j(d,b),d}function G(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo,e=d.db.transaction(d.storeName,"readonly").objectStore(d.storeName),f=e.openCursor(),g=[];f.onsuccess=function(){var b=f.result;return b?(g.push(b.key),void b["continue"]()):void a(g)},f.onerror=function(){c(f.error)}})["catch"](c)});return j(c,a),c}function H(a){var b,c,d,e,f,g=.75*a.length,h=a.length,i=0;"="===a[a.length-1]&&(g--,"="===a[a.length-2]&&g--);var j=new ArrayBuffer(g),k=new Uint8Array(j);for(b=0;h>b;b+=4)c=ma.indexOf(a[b]),d=ma.indexOf(a[b+1]),e=ma.indexOf(a[b+2]),f=ma.indexOf(a[b+3]),k[i++]=c<<2|d>>4,k[i++]=(15&d)<<4|e>>2,k[i++]=(3&e)<<6|63&f;return j}function I(a){var b,c=new Uint8Array(a),d="";for(b=0;b<c.length;b+=3)d+=ma[c[b]>>2],d+=ma[(3&c[b])<<4|c[b+1]>>4],d+=ma[(15&c[b+1])<<2|c[b+2]>>6],d+=ma[63&c[b+2]];return c.length%3===2?d=d.substring(0,d.length-1)+"=":c.length%3===1&&(d=d.substring(0,d.length-2)+"=="),d}function J(a,b){var c="";if(a&&(c=Da.call(a)),a&&("[object ArrayBuffer]"===c||a.buffer&&"[object ArrayBuffer]"===Da.call(a.buffer))){var d,e=pa;a instanceof ArrayBuffer?(d=a,e+=ra):(d=a.buffer,"[object Int8Array]"===c?e+=ta:"[object Uint8Array]"===c?e+=ua:"[object Uint8ClampedArray]"===c?e+=va:"[object Int16Array]"===c?e+=wa:"[object Uint16Array]"===c?e+=ya:"[object Int32Array]"===c?e+=xa:"[object Uint32Array]"===c?e+=za:"[object Float32Array]"===c?e+=Aa:"[object Float64Array]"===c?e+=Ba:b(new Error("Failed to get type for BinaryArray"))),b(e+I(d))}else if("[object Blob]"===c){var f=new FileReader;f.onload=function(){var c=na+a.type+"~"+I(this.result);b(pa+sa+c)},f.readAsArrayBuffer(a)}else try{b(JSON.stringify(a))}catch(g){console.error("Couldn't convert value into a JSON string: ",a),b(null,g)}}function K(a){if(a.substring(0,qa)!==pa)return JSON.parse(a);var b,c=a.substring(Ca),d=a.substring(qa,Ca);if(d===sa&&oa.test(c)){var e=c.match(oa);b=e[1],c=c.substring(e[0].length)}var f=H(c);switch(d){case ra:return f;case sa:return i([f],{type:b});case ta:return new Int8Array(f);case ua:return new Uint8Array(f);case va:return new Uint8ClampedArray(f);case wa:return new Int16Array(f);case ya:return new Uint16Array(f);case xa:return new Int32Array(f);case za:return new Uint32Array(f);case Aa:return new Float32Array(f);case Ba:return new Float64Array(f);default:throw new Error("Unkown type: "+d)}}function L(a){var b=this,c={db:null};if(a)for(var d in a)c[d]="string"!=typeof a[d]?a[d].toString():a[d];var e=new ia(function(a,d){try{c.db=openDatabase(c.name,String(c.version),c.description,c.size)}catch(e){return d(e)}c.db.transaction(function(e){e.executeSql("CREATE TABLE IF NOT EXISTS "+c.storeName+" (id INTEGER PRIMARY KEY, key unique, value)",[],function(){b._dbInfo=c,a()},function(a,b){d(b)})})});return c.serializer=Ea,e}function M(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo;e.db.transaction(function(c){c.executeSql("SELECT * FROM "+e.storeName+" WHERE key = ? LIMIT 1",[a],function(a,c){var d=c.rows.length?c.rows.item(0).value:null;d&&(d=e.serializer.deserialize(d)),b(d)},function(a,b){d(b)})})})["catch"](d)});return j(d,b),d}function N(a,b){var c=this,d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo;e.db.transaction(function(c){c.executeSql("SELECT * FROM "+e.storeName,[],function(c,d){for(var f=d.rows,g=f.length,h=0;g>h;h++){var i=f.item(h),j=i.value;if(j&&(j=e.serializer.deserialize(j)),j=a(j,i.key,h+1),void 0!==j)return void b(j)}b()},function(a,b){d(b)})})})["catch"](d)});return j(d,b),d}function O(a,b,c){var d=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var e=new ia(function(c,e){d.ready().then(function(){void 0===b&&(b=null);var f=b,g=d._dbInfo;g.serializer.serialize(b,function(b,d){d?e(d):g.db.transaction(function(d){d.executeSql("INSERT OR REPLACE INTO "+g.storeName+" (key, value) VALUES (?, ?)",[a,b],function(){c(f)},function(a,b){e(b)})},function(a){a.code===a.QUOTA_ERR&&e(a)})})})["catch"](e)});return j(e,c),e}function P(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo;e.db.transaction(function(c){c.executeSql("DELETE FROM "+e.storeName+" WHERE key = ?",[a],function(){b()},function(a,b){d(b)})})})["catch"](d)});return j(d,b),d}function Q(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo;d.db.transaction(function(b){b.executeSql("DELETE FROM "+d.storeName,[],function(){a()},function(a,b){c(b)})})})["catch"](c)});return j(c,a),c}function R(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo;d.db.transaction(function(b){b.executeSql("SELECT COUNT(key) as c FROM "+d.storeName,[],function(b,c){var d=c.rows.item(0).c;a(d)},function(a,b){c(b)})})})["catch"](c)});return j(c,a),c}function S(a,b){var c=this,d=new ia(function(b,d){c.ready().then(function(){var e=c._dbInfo;e.db.transaction(function(c){c.executeSql("SELECT key FROM "+e.storeName+" WHERE id = ? LIMIT 1",[a+1],function(a,c){var d=c.rows.length?c.rows.item(0).key:null;b(d)},function(a,b){d(b)})})})["catch"](d)});return j(d,b),d}function T(a){var b=this,c=new ia(function(a,c){b.ready().then(function(){var d=b._dbInfo;d.db.transaction(function(b){b.executeSql("SELECT key FROM "+d.storeName,[],function(b,c){for(var d=[],e=0;e<c.rows.length;e++)d.push(c.rows.item(e).key);a(d)},function(a,b){c(b)})})})["catch"](c)});return j(c,a),c}function U(a){var b=this,c={};if(a)for(var d in a)c[d]=a[d];return c.keyPrefix=c.name+"/",c.storeName!==b._defaultConfig.storeName&&(c.keyPrefix+=c.storeName+"/"),b._dbInfo=c,c.serializer=Ea,ia.resolve()}function V(a){var b=this,c=b.ready().then(function(){for(var a=b._dbInfo.keyPrefix,c=localStorage.length-1;c>=0;c--){var d=localStorage.key(c);0===d.indexOf(a)&&localStorage.removeItem(d)}});return j(c,a),c}function W(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=c.ready().then(function(){var b=c._dbInfo,d=localStorage.getItem(b.keyPrefix+a);return d&&(d=b.serializer.deserialize(d)),d});return j(d,b),d}function X(a,b){var c=this,d=c.ready().then(function(){for(var b=c._dbInfo,d=b.keyPrefix,e=d.length,f=localStorage.length,g=1,h=0;f>h;h++){var i=localStorage.key(h);if(0===i.indexOf(d)){var j=localStorage.getItem(i);if(j&&(j=b.serializer.deserialize(j)),j=a(j,i.substring(e),g++),void 0!==j)return j}}});return j(d,b),d}function Y(a,b){var c=this,d=c.ready().then(function(){var b,d=c._dbInfo;try{b=localStorage.key(a)}catch(e){b=null}return b&&(b=b.substring(d.keyPrefix.length)),b});return j(d,b),d}function Z(a){var b=this,c=b.ready().then(function(){for(var a=b._dbInfo,c=localStorage.length,d=[],e=0;c>e;e++)0===localStorage.key(e).indexOf(a.keyPrefix)&&d.push(localStorage.key(e).substring(a.keyPrefix.length));return d});return j(c,a),c}function $(a){var b=this,c=b.keys().then(function(a){return a.length});return j(c,a),c}function _(a,b){var c=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var d=c.ready().then(function(){var b=c._dbInfo;localStorage.removeItem(b.keyPrefix+a)});return j(d,b),d}function aa(a,b,c){var d=this;"string"!=typeof a&&(console.warn(a+" used as a key, but it is not a string."),a=String(a));var e=d.ready().then(function(){void 0===b&&(b=null);var c=b;return new ia(function(e,f){var g=d._dbInfo;g.serializer.serialize(b,function(b,d){if(d)f(d);else try{localStorage.setItem(g.keyPrefix+a,b),e(c)}catch(h){"QuotaExceededError"!==h.name&&"NS_ERROR_DOM_QUOTA_REACHED"!==h.name||f(h),f(h)}})})});return j(e,c),e}function ba(a,b){a[b]=function(){var c=arguments;return a.ready().then(function(){return a[b].apply(a,c)})}}function ca(){for(var a=1;a<arguments.length;a++){var b=arguments[a];if(b)for(var c in b)b.hasOwnProperty(c)&&(Na(b[c])?arguments[0][c]=b[c].slice():arguments[0][c]=b[c])}return arguments[0]}function da(a){for(var b in Ia)if(Ia.hasOwnProperty(b)&&Ia[b]===a)return!0;return!1}var ea="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(a){return typeof a}:function(a){return a&&"function"==typeof Symbol&&a.constructor===Symbol?"symbol":typeof a},fa=e();"undefined"==typeof Promise&&"undefined"!=typeof a&&a(3);var ga,ha,ia=Promise,ja="local-forage-detect-blob-support",ka=Object.prototype.toString,la={_driver:"asyncStorage",_initStorage:y,iterate:A,getItem:z,setItem:B,removeItem:C,clear:D,length:E,key:F,keys:G},ma="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",na="~~local_forage_type~",oa=/^~~local_forage_type~([^~]+)~/,pa="__lfsc__:",qa=pa.length,ra="arbf",sa="blob",ta="si08",ua="ui08",va="uic8",wa="si16",xa="si32",ya="ur16",za="ui32",Aa="fl32",Ba="fl64",Ca=qa+ra.length,Da=Object.prototype.toString,Ea={serialize:J,deserialize:K,stringToBuffer:H,bufferToString:I},Fa={_driver:"webSQLStorage",_initStorage:L,iterate:N,getItem:M,setItem:O,removeItem:P,clear:Q,length:R,key:S,keys:T},Ga={_driver:"localStorageWrapper",_initStorage:U,iterate:X,getItem:W,setItem:aa,removeItem:_,clear:V,length:$,key:Y,keys:Z},Ha={},Ia={INDEXEDDB:"asyncStorage",LOCALSTORAGE:"localStorageWrapper",WEBSQL:"webSQLStorage"},Ja=[Ia.INDEXEDDB,Ia.WEBSQL,Ia.LOCALSTORAGE],Ka=["clear","getItem","iterate","key","keys","length","removeItem","setItem"],La={description:"",driver:Ja.slice(),name:"localforage",size:4980736,storeName:"keyvaluepairs",version:1},Ma={};Ma[Ia.INDEXEDDB]=f(),Ma[Ia.WEBSQL]=g(),Ma[Ia.LOCALSTORAGE]=h();var Na=Array.isArray||function(a){return"[object Array]"===Object.prototype.toString.call(a)},Oa=function(){function a(b){d(this,a),this.INDEXEDDB=Ia.INDEXEDDB,this.LOCALSTORAGE=Ia.LOCALSTORAGE,this.WEBSQL=Ia.WEBSQL,this._defaultConfig=ca({},La),this._config=ca({},this._defaultConfig,b),this._driverSet=null,this._initDriver=null,this._ready=!1,this._dbInfo=null,this._wrapLibraryMethodsWithReady(),this.setDriver(this._config.driver)}return a.prototype.config=function(a){if("object"===("undefined"==typeof a?"undefined":ea(a))){if(this._ready)return new Error("Can't call config() after localforage has been used.");for(var b in a)"storeName"===b&&(a[b]=a[b].replace(/\W/g,"_")),this._config[b]=a[b];return"driver"in a&&a.driver&&this.setDriver(this._config.driver),!0}return"string"==typeof a?this._config[a]:this._config},a.prototype.defineDriver=function(a,b,c){var d=new ia(function(b,c){try{var d=a._driver,e=new Error("Custom driver not compliant; see https://mozilla.github.io/localForage/#definedriver"),f=new Error("Custom driver name already in use: "+a._driver);if(!a._driver)return void c(e);if(da(a._driver))return void c(f);for(var g=Ka.concat("_initStorage"),h=0;h<g.length;h++){var i=g[h];if(!i||!a[i]||"function"!=typeof a[i])return void c(e)}var j=ia.resolve(!0);"_support"in a&&(j=a._support&&"function"==typeof a._support?a._support():ia.resolve(!!a._support)),j.then(function(c){Ma[d]=c,Ha[d]=a,b()},c)}catch(k){c(k)}});return k(d,b,c),d},a.prototype.driver=function(){return this._driver||null},a.prototype.getDriver=function(a,b,c){var d=this,e=ia.resolve().then(function(){if(!da(a)){if(Ha[a])return Ha[a];throw new Error("Driver not found.")}switch(a){case d.INDEXEDDB:return la;case d.LOCALSTORAGE:return Ga;case d.WEBSQL:return Fa}});return k(e,b,c),e},a.prototype.getSerializer=function(a){var b=ia.resolve(Ea);return k(b,a),b},a.prototype.ready=function(a){var b=this,c=b._driverSet.then(function(){return null===b._ready&&(b._ready=b._initDriver()),b._ready});return k(c,a,a),c},a.prototype.setDriver=function(a,b,c){function d(){f._config.driver=f.driver()}function e(a){return function(){function b(){for(;c<a.length;){var e=a[c];return c++,f._dbInfo=null,f._ready=null,f.getDriver(e).then(function(a){return f._extend(a),d(),f._ready=f._initStorage(f._config),f._ready})["catch"](b)}d();var g=new Error("No available storage method found.");return f._driverSet=ia.reject(g),f._driverSet}var c=0;return b()}}var f=this;Na(a)||(a=[a]);var g=this._getSupportedDrivers(a),h=null!==this._driverSet?this._driverSet["catch"](function(){return ia.resolve()}):ia.resolve();return this._driverSet=h.then(function(){var a=g[0];return f._dbInfo=null,f._ready=null,f.getDriver(a).then(function(a){f._driver=a._driver,d(),f._wrapLibraryMethodsWithReady(),f._initDriver=e(g)})})["catch"](function(){d();var a=new Error("No available storage method found.");return f._driverSet=ia.reject(a),f._driverSet}),k(this._driverSet,b,c),this._driverSet},a.prototype.supports=function(a){return!!Ma[a]},a.prototype._extend=function(a){ca(this,a)},a.prototype._getSupportedDrivers=function(a){for(var b=[],c=0,d=a.length;d>c;c++){var e=a[c];this.supports(e)&&b.push(e)}return b},a.prototype._wrapLibraryMethodsWithReady=function(){for(var a=0;a<Ka.length;a++)ba(this,Ka[a])},a.prototype.createInstance=function(b){return new a(b)},a}(),Pa=new Oa;b.exports=Pa},{3:3}]},{},[4])(4)});                                                            
window.URL = window.URL || window.webkitURL || null;

window.Base64ToBlob = function(dataURL) {
  var BASE64_MARKER = ';base64,';
  if (dataURL.indexOf(BASE64_MARKER) == -1) {
    var parts = dataURL.split(',');
    var contentType = parts[0].split(':')[1];
    var raw = decodeURIComponent(parts[1]);

    return new Blob([raw], {type: contentType});
  }

  var parts = dataURL.split(BASE64_MARKER);
  var contentType = parts[0].split(':')[1];
  var raw = window.atob(parts[1].replace(/\n/g,''));
  var rawLength = raw.length;

  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], {type: contentType});
};

window.getParameterByName = function ( name ){
    var regexS = "[\\?&]"+name+"=([^&#]*)", 
  regex = new RegExp( regexS ),
  results = regex.exec( window.location.search );
  if( results == null ){
    return "";
  } else{
    return decodeURIComponent(results[1].replace(/\+/g, " "));
  }
};

window.BlobToBase64 = function(blob, onload) {
  var reader = new FileReader();
  reader.readAsDataURL(blob);
  reader.onloadend = function() {
    onload(reader.result);
  };
};

window.arrayBufferToBase64 = function(buffer){
    var binary = '';
    var bytes = new Uint8Array( buffer );
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode( bytes[ i ] );
    }
    return window.btoa( binary );
};

window.getCorsProxyURL = function(){
    return window.cn1CORSProxyURL || null;
};

window.getCN1DeploymentType = function(){
    return window.cn1DeploymentType || "war";
};



(function(exports) {

// do nothing if the Storage Info API is already available
if (exports.webkitStorageInfo) {
  return;
}

if (typeof exports.TEMPORARY == "undefined") {
  exports.TEMPORARY = 0,
  exports.PERSISTENT = 1
}

exports.webkitStorageInfo = {
  TEMPORARY: exports.TEMPORARY,
  PERSISTENT: exports.PERSISTENT,
}

var UNSUPPORTED_STORAGE_TYPE = "Unsupported storage type";

function requestQuota(type, size, successCallback, errorCallback) {
  if (type != exports.TEMPORARY && type != exports.PERSISTENT) {
    if (errorCallback) {
      errorCallback(UNSUPPORTED_STORAGE_TYPE);
    }
    return;
  }
  successCallback(size);
}
function queryUsageAndQuota(type, successCallback, errorCallback) {
  if (type != exports.TEMPORARY && type != exports.PERSISTENT) {
    if (errorCallback) {
      errorCallback(UNSUPPORTED_STORAGE_TYPE);
    }
    return;
  }
  successCallback(0, 0);
}
exports.webkitStorageInfo.requestQuota = requestQuota;
exports.webkitStorageInfo.queryUsageAndQuota = queryUsageAndQuota;

})(window);


(function(){
var $ = jQuery;
if (!HTMLCanvasElement.prototype.toBlob) {
 Object.defineProperty(HTMLCanvasElement.prototype, 'toBlob', {
  value: function (callback, type, quality) {

    var binStr = atob( this.toDataURL(type, quality).split(',')[1] ),
        len = binStr.length,
        arr = new Uint8Array(len);

    for (var i=0; i<len; i++ ) {
     arr[i] = binStr.charCodeAt(i);
    }

    callback( new Blob( [arr], {type: type || 'image/png'} ) );
  }
 });
}
window.requestFileSystem  = window.requestFileSystem || window.webkitRequestFileSystem;
navigator.persistentStorage = navigator.persistentStorage || navigator.webkitPersistentStorage;
navigator.temporaryStorage = navigator.temporaryStorage || navigator.webkitTemporaryStorage;
navigator.getUserMedia  = navigator.getUserMedia ||
                          navigator.webkitGetUserMedia ||
                          navigator.mozGetUserMedia ||
                          navigator.msGetUserMedia;
    
    
  window.cn1 = {};
  var cn1 = window.cn1;


  var measureText = CanvasRenderingContext2D.prototype.measureText;
  CanvasRenderingContext2D.prototype.measureText = function(textstring) {
      var metrics = measureText.call(this, textstring);
      if (typeof(metrics.height)=== 'undefined'){
          metrics.height = parseInt(/[0-9]+(?=pt|px)/.exec(this.font));
          
      }
      if (metrics.ascent === undefined) {
          metrics.ascent = measureText.call(this, "M").width;
      }
      
      if (metrics.descent === undefined) {
          metrics.descent = metrics.height - metrics.ascent;
      }
      return metrics;
  }
 

    var pixel = null;
    var measureTextNode = null;
    var measureTextCache = null;
    window.measureAscentDescent = function (fontFamily) {
        measureTextCache = measureTextCache || {};
        if (measureTextCache[fontFamily]) {
            return measureTextCache[fontFamily];
        }
        measureTextNode = measureTextNode || jQuery('<div style="position:absolute; top:0; left:0; height: 200px; line-height:1.0;padding:0;display:none">TheQuickBrownFoxquickly!</div>').get(0);
        pixel = pixel || jQuery('<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==" width="42" height="1"/>').get(0);
        if (measureTextNode.parentNode != jQuery('body').get(0)) {
            jQuery('body').append(measureTextNode);
        }
        if (pixel.parentNode != measureTextNode.parentNode) {
            
            measureTextNode.appendChild(pixel);
        }
        
        
        measureTextNode.style.fontFamily = fontFamily;
        measureTextNode.style.fontSize = "100px";
        pixel.style.verticalAlign = "text-top";
        measureTextNode.style.display='';
        var top = pixel.offsetTop - measureTextNode.offsetTop + 1;
        pixel.style.verticalAlign = "baseline";
        var baseline = pixel.offsetTop - measureTextNode.offsetTop + 1;
        
        pixel.style.verticalAlign="text-bottom";
        var bottom = pixel.offsetTop - measureTextNode.offsetTop + 1;
        
        var result = {
            ascent: (baseline-top)/100.0,
            descent: (bottom-baseline)/100.0
        };
        measureTextNode.style.display='none';
        measureTextCache[fontFamily] = result;
        //console.log(fontFamily, result);
        return result;
    }
  
    window.measureTextAscent = function(fontFamily) { return window.measureAscentDescent(fontFamily).ascent;}
    window.measureTextDescent = function(fontFamily) { return window.measureAscentDescent(fontFamily).descent;} 
  
  
    var isMobile = {
        Android: function() {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function() {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function() {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function() {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function() {
            return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
        },
        any: function() {
            
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    
    cn1.isMobile = isMobile;
    
    
    function simulateClick(el) {
        var evt;
        if (document.createEvent) {
            evt = document.createEvent("MouseEvents");
            evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        }
        (evt) ? el.dispatchEvent(evt) : (el.click && el.click());
    }
    
    function capturePhotoWithFileButton(callback, targetWidth, targetHeight){
        var fileBtn = $('<input id="cn1-image-picker" type="file" accept="image/*" />');
        var dialog = document.createElement('div');
        dialog.appendChild(fileBtn.get(0));
        dialog.className = 'cn1-capture-dialog';

        document.querySelector('body').appendChild(dialog);
        fileBtn.change(function(event){
            var files = event.target.files;
            if (files.length>0){
            
                var reader = new FileReader();
                var img = $('<img>').get(0);
                $(img).on('load', function(){
                    $(dialog).fadeOut(100, function(){
                        dialog.parentNode.removeChild(dialog);
                    });
                    var width=targetWidth;
                    var height=targetHeight;
                    if (width<img.naturalWidth){
                        height=height*width/img.naturalWidth;
                        width=img.naturalWidth;
                    }
                    if (height<img.naturalHeight){
                        width=width*height/img.naturalHeight;
                        height=img.naturalHeight;
                    }
                    var outCanvas = document.createElement('canvas');
                    outCanvas.setAttribute('width', width);
                    outCanvas.setAttribute('height', height);
                    var ctx = outCanvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    callback(outCanvas);
                });
                $(img).on('error', function(){
                    console.log('Error loading image');
                    $(dialog).fadeOut(100, function(){
                        dialog.parentNode.removeChild(dialog);
                    });
                    callback(null);
                });
                reader.onload = function(e){
                    img.src=e.target.result;
                };
                reader.readAsDataURL(files[0]);
                
                
            }
        });
    }
    cn1.capturePhoto = navigator.getUserMedia ? function(){} : 
            capturePhotoWithFileButton;
    
    
    // Used for the Preview function for embedding all assets in the single HTML file
    // as data URLs
    cn1.getBundledAssetAsDataURL = function(assetName){
        if (assetName.indexOf('/') != -1) {
            assetName = assetName.substr(assetName.indexOf('/')+1);
        }
        var assets = window.cn1Assets || {};
        return assets[assetName] || null;
    };
    
    
    cn1.proxifyContent = function(url, pageContent, iframe) {
        var $ = jQuery;
        
        var parser = new DOMParser();
        var xmls = new XMLSerializer();
        
        var doc = parser.parseFromString(pageContent, "text/html");
        
        function resolve(url, base_url, doc) {
          var old_base = doc.getElementsByTagName('base')[0]
            , old_href = old_base && old_base.href
            , doc_head = doc.head || doc.getElementsByTagName('head')[0]
            , our_base = old_base || doc_head.appendChild(doc.createElement('base'))
            , resolver = doc.createElement('a')
            , resolved_url
            ;
          our_base.href = base_url || '';
          resolver.href = url;
          resolved_url  = resolver.href; // browser magic at work here
        
          if (old_base) old_base.href = old_href;
          else doc_head.removeChild(our_base);
          return resolved_url;
        }
        
        $('[href]', doc).each(function() {
            var absUrl = resolve($(this).attr('href'), url, doc);
            $(this).attr('href', absUrl);
        });
        $('[src]', doc).each(function() {
            var absUrl = resolve($(this).attr('src'), url, doc);
            $(this).attr('src', absUrl);
        });
        
        pageContent = xmls.serializeToString(doc);
        // This fixes a bug with wrongly encoded comments in script tags.  gah!!
        pageContent = pageContent.replace(/>&lt;!--/g, '><!--').replace(/--&gt;</g, '--><');
        //console.log(pageContent);
        
        iframe.contentWindow.document.open();
        iframe.contentWindow.document.write(pageContent);
        iframe.contentWindow.document.close();
        doc = iframe.contentWindow.document;
        var whenReady = function() { 
          var links = doc.querySelectorAll('a[href]');
          [].forEach.call(links, function(el, index, array) {
              el.addEventListener("click", function(evt) {
                  var absUrl = resolve(el.getAttribute('href'), url, doc);
                  $(iframe).trigger('cn1load', [$rt_str(absUrl)]);
                  evt.preventDefault();
                  return false;
              });
          });
        };
        if (doc.readyState == 'complete') {
            whenReady();
        } else {
            doc.addEventListener("DOMContentLoaded", whenReady);

        }
        

       
    };
    
})();
