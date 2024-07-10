import{cn as Mt,Z as M,T as j,aM as De,K as L,a8 as Je,M as vn,aa as Te,a0 as Bt,co as gn,a3 as mn,a4 as hn,I as bn,N as ee,u as d,ae as Ae,a as $,r as N,V as se,X as ue,ad as Se,R as le,aP as Ke,o as U,f as He,W as Ze,bV as Ge,ag as It,cp as yn,bH as wn,G as On,cq as En,cr as Tn,i as xe,cs as _t,bI as We,be as jt,c as ve,j as te,D as Dt,m as de,aN as ht,a1 as Cn,ai as Pn,A as An,an as Nt,b4 as bt,aG as xn,b6 as Le,ak as Rn,al as kn,am as Sn,ct as Mn,a2 as Bn,b5 as In,cu as _n,t as jn}from"./entry.3d67c050.js";const Y=(e,t,{checkForDefaultPrevented:n=!0}={})=>r=>{const a=e==null?void 0:e(r);if(n===!1||!a)return t==null?void 0:t(r)},$r=e=>t=>t.pointerType==="mouse"?e(t):void 0,Dn=()=>Math.floor(Math.random()*1e4),Xe=Symbol("popper"),Lt=Symbol("popperContent"),Ye=Symbol("elTooltip"),Nn=Mt({type:M(Boolean),default:null}),Ln=Mt({type:M(Function)}),$n=e=>{const t=`update:${e}`,n=`onUpdate:${e}`,o=[t],r={[e]:Nn,[n]:Ln};return{useModelToggle:({indicator:i,toggleReason:s,shouldHideWhenRouteChanges:l,shouldProceed:u,onShow:f,onHide:v})=>{const b=vn(),{emit:g}=b,m=b.props,c=j(()=>De(m[n])),O=j(()=>m[e]===null),p=w=>{i.value!==!0&&(i.value=!0,s&&(s.value=w),De(f)&&f(w))},E=w=>{i.value!==!1&&(i.value=!1,s&&(s.value=w),De(v)&&v(w))},C=w=>{if(m.disabled===!0||De(u)&&!u())return;const x=c.value&&Te;x&&g(t,!0),(O.value||!x)&&p(w)},y=w=>{if(m.disabled===!0||!Te)return;const x=c.value&&Te;x&&g(t,!1),(O.value||!x)&&E(w)},P=w=>{!Bt(w)||(m.disabled&&w?c.value&&g(t,!1):i.value!==w&&(w?p():E()))},A=()=>{i.value?y():C()};return L(()=>m[e],P),l&&b.appContext.config.globalProperties.$route!==void 0&&L(()=>({...b.proxy.$route}),()=>{l.value&&i.value&&y()}),Je(()=>{P(m[e])}),{hide:y,show:C,toggle:A,hasUpdateHandler:c}},useModelToggleProps:r,useModelToggleEmits:o}};function Hn(){let e;const t=(o,r)=>{n(),e=window.setTimeout(o,r)},n=()=>window.clearTimeout(e);return gn(()=>n()),{registerTimeout:t,cancelTimeout:n}}let yt;const Wn=mn("namespace",hn),$t=`${Wn.value}-popper-container-${Dn()}`,Ht=`#${$t}`,Fn=()=>{const e=document.createElement("div");return e.id=$t,document.body.appendChild(e),e},Kn=()=>{bn(()=>{!Te||(!yt||!document.body.querySelector(Ht))&&(yt=Fn())})},zn=ee({showAfter:{type:Number,default:0},hideAfter:{type:Number,default:200}}),qn=({showAfter:e,hideAfter:t,open:n,close:o})=>{const{registerTimeout:r}=Hn();return{onOpen:s=>{r(()=>{n(s)},d(e))},onClose:s=>{r(()=>{o(s)},d(t))}}},Wt=Symbol("elForwardRef"),Vn=e=>{Ae(Wt,{setForwardRef:n=>{e.value=n}})},Gn=e=>({mounted(t){e(t)},updated(t){e(t)},unmounted(){e(null)}}),Hr={LIGHT:"light",DARK:"dark"},Un=["dialog","grid","group","listbox","menu","navigation","tooltip","tree"],Ft=ee({role:{type:String,values:Un,default:"tooltip"}}),Jn=$({name:"ElPopperRoot",inheritAttrs:!1}),Zn=$({...Jn,props:Ft,setup(e,{expose:t}){const n=e,o=N(),r=N(),a=N(),i=N(),s=j(()=>n.role),l={triggerRef:o,popperInstanceRef:r,contentRef:a,referenceRef:i,role:s};return t(l),Ae(Xe,l),(u,f)=>se(u.$slots,"default")}});var Xn=ue(Zn,[["__file","/home/runner/work/element-plus/element-plus/packages/components/popper/src/popper.vue"]]);const Kt=ee({arrowOffset:{type:Number,default:5}}),Yn=$({name:"ElPopperArrow",inheritAttrs:!1}),Qn=$({...Yn,props:Kt,setup(e,{expose:t}){const n=e,o=Se("popper"),{arrowOffset:r,arrowRef:a}=le(Lt,void 0);return L(()=>n.arrowOffset,i=>{r.value=i}),Ke(()=>{a.value=void 0}),t({arrowRef:a}),(i,s)=>(U(),He("span",{ref_key:"arrowRef",ref:a,class:Ze(d(o).e("arrow")),"data-popper-arrow":""},null,2))}});var eo=ue(Qn,[["__file","/home/runner/work/element-plus/element-plus/packages/components/popper/src/arrow.vue"]]);const to="ElOnlyChild",no=$({name:to,setup(e,{slots:t,attrs:n}){var o;const r=le(Wt),a=Gn((o=r==null?void 0:r.setForwardRef)!=null?o:Ge);return()=>{var i;const s=(i=t.default)==null?void 0:i.call(t,n);if(!s||s.length>1)return null;const l=zt(s);return l?It(yn(l,n),[[a]]):null}}});function zt(e){if(!e)return null;const t=e;for(const n of t){if(wn(n))switch(n.type){case Tn:continue;case En:case"svg":return wt(n);case On:return zt(n.children);default:return n}return wt(n)}return null}function wt(e){const t=Se("only-child");return xe("span",{class:t.e("content")},[e])}const qt=ee({virtualRef:{type:M(Object)},virtualTriggering:Boolean,onMouseenter:{type:M(Function)},onMouseleave:{type:M(Function)},onClick:{type:M(Function)},onKeydown:{type:M(Function)},onFocus:{type:M(Function)},onBlur:{type:M(Function)},onContextmenu:{type:M(Function)},id:String,open:Boolean}),oo=$({name:"ElPopperTrigger",inheritAttrs:!1}),ro=$({...oo,props:qt,setup(e,{expose:t}){const n=e,{role:o,triggerRef:r}=le(Xe,void 0);Vn(r);const a=j(()=>s.value?n.id:void 0),i=j(()=>{if(o&&o.value==="tooltip")return n.open&&n.id?n.id:void 0}),s=j(()=>{if(o&&o.value!=="tooltip")return o.value}),l=j(()=>s.value?`${n.open}`:void 0);let u;return Je(()=>{L(()=>n.virtualRef,f=>{f&&(r.value=_t(f))},{immediate:!0}),L(r,(f,v)=>{u==null||u(),u=void 0,We(f)&&(["onMouseenter","onMouseleave","onClick","onKeydown","onFocus","onBlur","onContextmenu"].forEach(b=>{var g;const m=n[b];m&&(f.addEventListener(b.slice(2).toLowerCase(),m),(g=v==null?void 0:v.removeEventListener)==null||g.call(v,b.slice(2).toLowerCase(),m))}),u=L([a,i,s,l],b=>{["aria-controls","aria-describedby","aria-haspopup","aria-expanded"].forEach((g,m)=>{jt(b[m])?f.removeAttribute(g):f.setAttribute(g,b[m])})},{immediate:!0})),We(v)&&["aria-controls","aria-describedby","aria-haspopup","aria-expanded"].forEach(b=>v.removeAttribute(b))},{immediate:!0})}),Ke(()=>{u==null||u(),u=void 0}),t({triggerRef:r}),(f,v)=>f.virtualTriggering?de("v-if",!0):(U(),ve(d(no),Dt({key:0},f.$attrs,{"aria-controls":d(a),"aria-describedby":d(i),"aria-expanded":d(l),"aria-haspopup":d(s)}),{default:te(()=>[se(f.$slots,"default")]),_:3},16,["aria-controls","aria-describedby","aria-expanded","aria-haspopup"]))}});var ao=ue(ro,[["__file","/home/runner/work/element-plus/element-plus/packages/components/popper/src/trigger.vue"]]),H="top",z="bottom",q="right",W="left",Qe="auto",Me=[H,z,q,W],ge="start",Re="end",io="clippingParents",Vt="viewport",Ee="popper",so="reference",Ot=Me.reduce(function(e,t){return e.concat([t+"-"+ge,t+"-"+Re])},[]),et=[].concat(Me,[Qe]).reduce(function(e,t){return e.concat([t,t+"-"+ge,t+"-"+Re])},[]),lo="beforeRead",uo="read",po="afterRead",fo="beforeMain",co="main",vo="afterMain",go="beforeWrite",mo="write",ho="afterWrite",bo=[lo,uo,po,fo,co,vo,go,mo,ho];function Z(e){return e?(e.nodeName||"").toLowerCase():null}function G(e){if(e==null)return window;if(e.toString()!=="[object Window]"){var t=e.ownerDocument;return t&&t.defaultView||window}return e}function me(e){var t=G(e).Element;return e instanceof t||e instanceof Element}function K(e){var t=G(e).HTMLElement;return e instanceof t||e instanceof HTMLElement}function tt(e){if(typeof ShadowRoot>"u")return!1;var t=G(e).ShadowRoot;return e instanceof t||e instanceof ShadowRoot}function yo(e){var t=e.state;Object.keys(t.elements).forEach(function(n){var o=t.styles[n]||{},r=t.attributes[n]||{},a=t.elements[n];!K(a)||!Z(a)||(Object.assign(a.style,o),Object.keys(r).forEach(function(i){var s=r[i];s===!1?a.removeAttribute(i):a.setAttribute(i,s===!0?"":s)}))})}function wo(e){var t=e.state,n={popper:{position:t.options.strategy,left:"0",top:"0",margin:"0"},arrow:{position:"absolute"},reference:{}};return Object.assign(t.elements.popper.style,n.popper),t.styles=n,t.elements.arrow&&Object.assign(t.elements.arrow.style,n.arrow),function(){Object.keys(t.elements).forEach(function(o){var r=t.elements[o],a=t.attributes[o]||{},i=Object.keys(t.styles.hasOwnProperty(o)?t.styles[o]:n[o]),s=i.reduce(function(l,u){return l[u]="",l},{});!K(r)||!Z(r)||(Object.assign(r.style,s),Object.keys(a).forEach(function(l){r.removeAttribute(l)}))})}}var Gt={name:"applyStyles",enabled:!0,phase:"write",fn:yo,effect:wo,requires:["computeStyles"]};function J(e){return e.split("-")[0]}var ie=Math.max,Fe=Math.min,he=Math.round;function be(e,t){t===void 0&&(t=!1);var n=e.getBoundingClientRect(),o=1,r=1;if(K(e)&&t){var a=e.offsetHeight,i=e.offsetWidth;i>0&&(o=he(n.width)/i||1),a>0&&(r=he(n.height)/a||1)}return{width:n.width/o,height:n.height/r,top:n.top/r,right:n.right/o,bottom:n.bottom/r,left:n.left/o,x:n.left/o,y:n.top/r}}function nt(e){var t=be(e),n=e.offsetWidth,o=e.offsetHeight;return Math.abs(t.width-n)<=1&&(n=t.width),Math.abs(t.height-o)<=1&&(o=t.height),{x:e.offsetLeft,y:e.offsetTop,width:n,height:o}}function Ut(e,t){var n=t.getRootNode&&t.getRootNode();if(e.contains(t))return!0;if(n&&tt(n)){var o=t;do{if(o&&e.isSameNode(o))return!0;o=o.parentNode||o.host}while(o)}return!1}function Q(e){return G(e).getComputedStyle(e)}function Oo(e){return["table","td","th"].indexOf(Z(e))>=0}function ne(e){return((me(e)?e.ownerDocument:e.document)||window.document).documentElement}function ze(e){return Z(e)==="html"?e:e.assignedSlot||e.parentNode||(tt(e)?e.host:null)||ne(e)}function Et(e){return!K(e)||Q(e).position==="fixed"?null:e.offsetParent}function Eo(e){var t=navigator.userAgent.toLowerCase().indexOf("firefox")!==-1,n=navigator.userAgent.indexOf("Trident")!==-1;if(n&&K(e)){var o=Q(e);if(o.position==="fixed")return null}var r=ze(e);for(tt(r)&&(r=r.host);K(r)&&["html","body"].indexOf(Z(r))<0;){var a=Q(r);if(a.transform!=="none"||a.perspective!=="none"||a.contain==="paint"||["transform","perspective"].indexOf(a.willChange)!==-1||t&&a.willChange==="filter"||t&&a.filter&&a.filter!=="none")return r;r=r.parentNode}return null}function Be(e){for(var t=G(e),n=Et(e);n&&Oo(n)&&Q(n).position==="static";)n=Et(n);return n&&(Z(n)==="html"||Z(n)==="body"&&Q(n).position==="static")?t:n||Eo(e)||t}function ot(e){return["top","bottom"].indexOf(e)>=0?"x":"y"}function Ce(e,t,n){return ie(e,Fe(t,n))}function To(e,t,n){var o=Ce(e,t,n);return o>n?n:o}function Jt(){return{top:0,right:0,bottom:0,left:0}}function Zt(e){return Object.assign({},Jt(),e)}function Xt(e,t){return t.reduce(function(n,o){return n[o]=e,n},{})}var Co=function(e,t){return e=typeof e=="function"?e(Object.assign({},t.rects,{placement:t.placement})):e,Zt(typeof e!="number"?e:Xt(e,Me))};function Po(e){var t,n=e.state,o=e.name,r=e.options,a=n.elements.arrow,i=n.modifiersData.popperOffsets,s=J(n.placement),l=ot(s),u=[W,q].indexOf(s)>=0,f=u?"height":"width";if(!(!a||!i)){var v=Co(r.padding,n),b=nt(a),g=l==="y"?H:W,m=l==="y"?z:q,c=n.rects.reference[f]+n.rects.reference[l]-i[l]-n.rects.popper[f],O=i[l]-n.rects.reference[l],p=Be(a),E=p?l==="y"?p.clientHeight||0:p.clientWidth||0:0,C=c/2-O/2,y=v[g],P=E-b[f]-v[m],A=E/2-b[f]/2+C,w=Ce(y,A,P),x=l;n.modifiersData[o]=(t={},t[x]=w,t.centerOffset=w-A,t)}}function Ao(e){var t=e.state,n=e.options,o=n.element,r=o===void 0?"[data-popper-arrow]":o;r!=null&&(typeof r=="string"&&(r=t.elements.popper.querySelector(r),!r)||!Ut(t.elements.popper,r)||(t.elements.arrow=r))}var xo={name:"arrow",enabled:!0,phase:"main",fn:Po,effect:Ao,requires:["popperOffsets"],requiresIfExists:["preventOverflow"]};function ye(e){return e.split("-")[1]}var Ro={top:"auto",right:"auto",bottom:"auto",left:"auto"};function ko(e){var t=e.x,n=e.y,o=window,r=o.devicePixelRatio||1;return{x:he(t*r)/r||0,y:he(n*r)/r||0}}function Tt(e){var t,n=e.popper,o=e.popperRect,r=e.placement,a=e.variation,i=e.offsets,s=e.position,l=e.gpuAcceleration,u=e.adaptive,f=e.roundOffsets,v=e.isFixed,b=i.x,g=b===void 0?0:b,m=i.y,c=m===void 0?0:m,O=typeof f=="function"?f({x:g,y:c}):{x:g,y:c};g=O.x,c=O.y;var p=i.hasOwnProperty("x"),E=i.hasOwnProperty("y"),C=W,y=H,P=window;if(u){var A=Be(n),w="clientHeight",x="clientWidth";if(A===G(n)&&(A=ne(n),Q(A).position!=="static"&&s==="absolute"&&(w="scrollHeight",x="scrollWidth")),A=A,r===H||(r===W||r===q)&&a===Re){y=z;var I=v&&A===P&&P.visualViewport?P.visualViewport.height:A[w];c-=I-o.height,c*=l?1:-1}if(r===W||(r===H||r===z)&&a===Re){C=q;var _=v&&A===P&&P.visualViewport?P.visualViewport.width:A[x];g-=_-o.width,g*=l?1:-1}}var B=Object.assign({position:s},u&&Ro),D=f===!0?ko({x:g,y:c}):{x:g,y:c};if(g=D.x,c=D.y,l){var h;return Object.assign({},B,(h={},h[y]=E?"0":"",h[C]=p?"0":"",h.transform=(P.devicePixelRatio||1)<=1?"translate("+g+"px, "+c+"px)":"translate3d("+g+"px, "+c+"px, 0)",h))}return Object.assign({},B,(t={},t[y]=E?c+"px":"",t[C]=p?g+"px":"",t.transform="",t))}function So(e){var t=e.state,n=e.options,o=n.gpuAcceleration,r=o===void 0?!0:o,a=n.adaptive,i=a===void 0?!0:a,s=n.roundOffsets,l=s===void 0?!0:s,u={placement:J(t.placement),variation:ye(t.placement),popper:t.elements.popper,popperRect:t.rects.popper,gpuAcceleration:r,isFixed:t.options.strategy==="fixed"};t.modifiersData.popperOffsets!=null&&(t.styles.popper=Object.assign({},t.styles.popper,Tt(Object.assign({},u,{offsets:t.modifiersData.popperOffsets,position:t.options.strategy,adaptive:i,roundOffsets:l})))),t.modifiersData.arrow!=null&&(t.styles.arrow=Object.assign({},t.styles.arrow,Tt(Object.assign({},u,{offsets:t.modifiersData.arrow,position:"absolute",adaptive:!1,roundOffsets:l})))),t.attributes.popper=Object.assign({},t.attributes.popper,{"data-popper-placement":t.placement})}var Yt={name:"computeStyles",enabled:!0,phase:"beforeWrite",fn:So,data:{}},Ne={passive:!0};function Mo(e){var t=e.state,n=e.instance,o=e.options,r=o.scroll,a=r===void 0?!0:r,i=o.resize,s=i===void 0?!0:i,l=G(t.elements.popper),u=[].concat(t.scrollParents.reference,t.scrollParents.popper);return a&&u.forEach(function(f){f.addEventListener("scroll",n.update,Ne)}),s&&l.addEventListener("resize",n.update,Ne),function(){a&&u.forEach(function(f){f.removeEventListener("scroll",n.update,Ne)}),s&&l.removeEventListener("resize",n.update,Ne)}}var Qt={name:"eventListeners",enabled:!0,phase:"write",fn:function(){},effect:Mo,data:{}},Bo={left:"right",right:"left",bottom:"top",top:"bottom"};function $e(e){return e.replace(/left|right|bottom|top/g,function(t){return Bo[t]})}var Io={start:"end",end:"start"};function Ct(e){return e.replace(/start|end/g,function(t){return Io[t]})}function rt(e){var t=G(e),n=t.pageXOffset,o=t.pageYOffset;return{scrollLeft:n,scrollTop:o}}function at(e){return be(ne(e)).left+rt(e).scrollLeft}function _o(e){var t=G(e),n=ne(e),o=t.visualViewport,r=n.clientWidth,a=n.clientHeight,i=0,s=0;return o&&(r=o.width,a=o.height,/^((?!chrome|android).)*safari/i.test(navigator.userAgent)||(i=o.offsetLeft,s=o.offsetTop)),{width:r,height:a,x:i+at(e),y:s}}function jo(e){var t,n=ne(e),o=rt(e),r=(t=e.ownerDocument)==null?void 0:t.body,a=ie(n.scrollWidth,n.clientWidth,r?r.scrollWidth:0,r?r.clientWidth:0),i=ie(n.scrollHeight,n.clientHeight,r?r.scrollHeight:0,r?r.clientHeight:0),s=-o.scrollLeft+at(e),l=-o.scrollTop;return Q(r||n).direction==="rtl"&&(s+=ie(n.clientWidth,r?r.clientWidth:0)-a),{width:a,height:i,x:s,y:l}}function it(e){var t=Q(e),n=t.overflow,o=t.overflowX,r=t.overflowY;return/auto|scroll|overlay|hidden/.test(n+r+o)}function en(e){return["html","body","#document"].indexOf(Z(e))>=0?e.ownerDocument.body:K(e)&&it(e)?e:en(ze(e))}function Pe(e,t){var n;t===void 0&&(t=[]);var o=en(e),r=o===((n=e.ownerDocument)==null?void 0:n.body),a=G(o),i=r?[a].concat(a.visualViewport||[],it(o)?o:[]):o,s=t.concat(i);return r?s:s.concat(Pe(ze(i)))}function Ue(e){return Object.assign({},e,{left:e.x,top:e.y,right:e.x+e.width,bottom:e.y+e.height})}function Do(e){var t=be(e);return t.top=t.top+e.clientTop,t.left=t.left+e.clientLeft,t.bottom=t.top+e.clientHeight,t.right=t.left+e.clientWidth,t.width=e.clientWidth,t.height=e.clientHeight,t.x=t.left,t.y=t.top,t}function Pt(e,t){return t===Vt?Ue(_o(e)):me(t)?Do(t):Ue(jo(ne(e)))}function No(e){var t=Pe(ze(e)),n=["absolute","fixed"].indexOf(Q(e).position)>=0,o=n&&K(e)?Be(e):e;return me(o)?t.filter(function(r){return me(r)&&Ut(r,o)&&Z(r)!=="body"}):[]}function Lo(e,t,n){var o=t==="clippingParents"?No(e):[].concat(t),r=[].concat(o,[n]),a=r[0],i=r.reduce(function(s,l){var u=Pt(e,l);return s.top=ie(u.top,s.top),s.right=Fe(u.right,s.right),s.bottom=Fe(u.bottom,s.bottom),s.left=ie(u.left,s.left),s},Pt(e,a));return i.width=i.right-i.left,i.height=i.bottom-i.top,i.x=i.left,i.y=i.top,i}function tn(e){var t=e.reference,n=e.element,o=e.placement,r=o?J(o):null,a=o?ye(o):null,i=t.x+t.width/2-n.width/2,s=t.y+t.height/2-n.height/2,l;switch(r){case H:l={x:i,y:t.y-n.height};break;case z:l={x:i,y:t.y+t.height};break;case q:l={x:t.x+t.width,y:s};break;case W:l={x:t.x-n.width,y:s};break;default:l={x:t.x,y:t.y}}var u=r?ot(r):null;if(u!=null){var f=u==="y"?"height":"width";switch(a){case ge:l[u]=l[u]-(t[f]/2-n[f]/2);break;case Re:l[u]=l[u]+(t[f]/2-n[f]/2);break}}return l}function ke(e,t){t===void 0&&(t={});var n=t,o=n.placement,r=o===void 0?e.placement:o,a=n.boundary,i=a===void 0?io:a,s=n.rootBoundary,l=s===void 0?Vt:s,u=n.elementContext,f=u===void 0?Ee:u,v=n.altBoundary,b=v===void 0?!1:v,g=n.padding,m=g===void 0?0:g,c=Zt(typeof m!="number"?m:Xt(m,Me)),O=f===Ee?so:Ee,p=e.rects.popper,E=e.elements[b?O:f],C=Lo(me(E)?E:E.contextElement||ne(e.elements.popper),i,l),y=be(e.elements.reference),P=tn({reference:y,element:p,strategy:"absolute",placement:r}),A=Ue(Object.assign({},p,P)),w=f===Ee?A:y,x={top:C.top-w.top+c.top,bottom:w.bottom-C.bottom+c.bottom,left:C.left-w.left+c.left,right:w.right-C.right+c.right},I=e.modifiersData.offset;if(f===Ee&&I){var _=I[r];Object.keys(x).forEach(function(B){var D=[q,z].indexOf(B)>=0?1:-1,h=[H,z].indexOf(B)>=0?"y":"x";x[B]+=_[h]*D})}return x}function $o(e,t){t===void 0&&(t={});var n=t,o=n.placement,r=n.boundary,a=n.rootBoundary,i=n.padding,s=n.flipVariations,l=n.allowedAutoPlacements,u=l===void 0?et:l,f=ye(o),v=f?s?Ot:Ot.filter(function(m){return ye(m)===f}):Me,b=v.filter(function(m){return u.indexOf(m)>=0});b.length===0&&(b=v);var g=b.reduce(function(m,c){return m[c]=ke(e,{placement:c,boundary:r,rootBoundary:a,padding:i})[J(c)],m},{});return Object.keys(g).sort(function(m,c){return g[m]-g[c]})}function Ho(e){if(J(e)===Qe)return[];var t=$e(e);return[Ct(e),t,Ct(t)]}function Wo(e){var t=e.state,n=e.options,o=e.name;if(!t.modifiersData[o]._skip){for(var r=n.mainAxis,a=r===void 0?!0:r,i=n.altAxis,s=i===void 0?!0:i,l=n.fallbackPlacements,u=n.padding,f=n.boundary,v=n.rootBoundary,b=n.altBoundary,g=n.flipVariations,m=g===void 0?!0:g,c=n.allowedAutoPlacements,O=t.options.placement,p=J(O),E=p===O,C=l||(E||!m?[$e(O)]:Ho(O)),y=[O].concat(C).reduce(function(re,X){return re.concat(J(X)===Qe?$o(t,{placement:X,boundary:f,rootBoundary:v,padding:u,flipVariations:m,allowedAutoPlacements:c}):X)},[]),P=t.rects.reference,A=t.rects.popper,w=new Map,x=!0,I=y[0],_=0;_<y.length;_++){var B=y[_],D=J(B),h=ye(B)===ge,T=[H,z].indexOf(D)>=0,R=T?"width":"height",k=ke(t,{placement:B,boundary:f,rootBoundary:v,altBoundary:b,padding:u}),S=T?h?q:W:h?z:H;P[R]>A[R]&&(S=$e(S));var V=$e(S),F=[];if(a&&F.push(k[D]<=0),s&&F.push(k[S]<=0,k[V]<=0),F.every(function(re){return re})){I=B,x=!1;break}w.set(B,F)}if(x)for(var oe=m?3:1,pe=function(re){var X=y.find(function(_e){var Oe=w.get(_e);if(Oe)return Oe.slice(0,re).every(function(fe){return fe})});if(X)return I=X,"break"},we=oe;we>0;we--){var Ie=pe(we);if(Ie==="break")break}t.placement!==I&&(t.modifiersData[o]._skip=!0,t.placement=I,t.reset=!0)}}var Fo={name:"flip",enabled:!0,phase:"main",fn:Wo,requiresIfExists:["offset"],data:{_skip:!1}};function At(e,t,n){return n===void 0&&(n={x:0,y:0}),{top:e.top-t.height-n.y,right:e.right-t.width+n.x,bottom:e.bottom-t.height+n.y,left:e.left-t.width-n.x}}function xt(e){return[H,q,z,W].some(function(t){return e[t]>=0})}function Ko(e){var t=e.state,n=e.name,o=t.rects.reference,r=t.rects.popper,a=t.modifiersData.preventOverflow,i=ke(t,{elementContext:"reference"}),s=ke(t,{altBoundary:!0}),l=At(i,o),u=At(s,r,a),f=xt(l),v=xt(u);t.modifiersData[n]={referenceClippingOffsets:l,popperEscapeOffsets:u,isReferenceHidden:f,hasPopperEscaped:v},t.attributes.popper=Object.assign({},t.attributes.popper,{"data-popper-reference-hidden":f,"data-popper-escaped":v})}var zo={name:"hide",enabled:!0,phase:"main",requiresIfExists:["preventOverflow"],fn:Ko};function qo(e,t,n){var o=J(e),r=[W,H].indexOf(o)>=0?-1:1,a=typeof n=="function"?n(Object.assign({},t,{placement:e})):n,i=a[0],s=a[1];return i=i||0,s=(s||0)*r,[W,q].indexOf(o)>=0?{x:s,y:i}:{x:i,y:s}}function Vo(e){var t=e.state,n=e.options,o=e.name,r=n.offset,a=r===void 0?[0,0]:r,i=et.reduce(function(f,v){return f[v]=qo(v,t.rects,a),f},{}),s=i[t.placement],l=s.x,u=s.y;t.modifiersData.popperOffsets!=null&&(t.modifiersData.popperOffsets.x+=l,t.modifiersData.popperOffsets.y+=u),t.modifiersData[o]=i}var Go={name:"offset",enabled:!0,phase:"main",requires:["popperOffsets"],fn:Vo};function Uo(e){var t=e.state,n=e.name;t.modifiersData[n]=tn({reference:t.rects.reference,element:t.rects.popper,strategy:"absolute",placement:t.placement})}var nn={name:"popperOffsets",enabled:!0,phase:"read",fn:Uo,data:{}};function Jo(e){return e==="x"?"y":"x"}function Zo(e){var t=e.state,n=e.options,o=e.name,r=n.mainAxis,a=r===void 0?!0:r,i=n.altAxis,s=i===void 0?!1:i,l=n.boundary,u=n.rootBoundary,f=n.altBoundary,v=n.padding,b=n.tether,g=b===void 0?!0:b,m=n.tetherOffset,c=m===void 0?0:m,O=ke(t,{boundary:l,rootBoundary:u,padding:v,altBoundary:f}),p=J(t.placement),E=ye(t.placement),C=!E,y=ot(p),P=Jo(y),A=t.modifiersData.popperOffsets,w=t.rects.reference,x=t.rects.popper,I=typeof c=="function"?c(Object.assign({},t.rects,{placement:t.placement})):c,_=typeof I=="number"?{mainAxis:I,altAxis:I}:Object.assign({mainAxis:0,altAxis:0},I),B=t.modifiersData.offset?t.modifiersData.offset[t.placement]:null,D={x:0,y:0};if(A){if(a){var h,T=y==="y"?H:W,R=y==="y"?z:q,k=y==="y"?"height":"width",S=A[y],V=S+O[T],F=S-O[R],oe=g?-x[k]/2:0,pe=E===ge?w[k]:x[k],we=E===ge?-x[k]:-w[k],Ie=t.elements.arrow,re=g&&Ie?nt(Ie):{width:0,height:0},X=t.modifiersData["arrow#persistent"]?t.modifiersData["arrow#persistent"].padding:Jt(),_e=X[T],Oe=X[R],fe=Ce(0,w[k],re[k]),sn=C?w[k]/2-oe-fe-_e-_.mainAxis:pe-fe-_e-_.mainAxis,ln=C?-w[k]/2+oe+fe+Oe+_.mainAxis:we+fe+Oe+_.mainAxis,qe=t.elements.arrow&&Be(t.elements.arrow),un=qe?y==="y"?qe.clientTop||0:qe.clientLeft||0:0,lt=(h=B==null?void 0:B[y])!=null?h:0,pn=S+sn-lt-un,fn=S+ln-lt,ut=Ce(g?Fe(V,pn):V,S,g?ie(F,fn):F);A[y]=ut,D[y]=ut-S}if(s){var pt,cn=y==="x"?H:W,dn=y==="x"?z:q,ae=A[P],je=P==="y"?"height":"width",ft=ae+O[cn],ct=ae-O[dn],Ve=[H,W].indexOf(p)!==-1,dt=(pt=B==null?void 0:B[P])!=null?pt:0,vt=Ve?ft:ae-w[je]-x[je]-dt+_.altAxis,gt=Ve?ae+w[je]+x[je]-dt-_.altAxis:ct,mt=g&&Ve?To(vt,ae,gt):Ce(g?vt:ft,ae,g?gt:ct);A[P]=mt,D[P]=mt-ae}t.modifiersData[o]=D}}var Xo={name:"preventOverflow",enabled:!0,phase:"main",fn:Zo,requiresIfExists:["offset"]};function Yo(e){return{scrollLeft:e.scrollLeft,scrollTop:e.scrollTop}}function Qo(e){return e===G(e)||!K(e)?rt(e):Yo(e)}function er(e){var t=e.getBoundingClientRect(),n=he(t.width)/e.offsetWidth||1,o=he(t.height)/e.offsetHeight||1;return n!==1||o!==1}function tr(e,t,n){n===void 0&&(n=!1);var o=K(t),r=K(t)&&er(t),a=ne(t),i=be(e,r),s={scrollLeft:0,scrollTop:0},l={x:0,y:0};return(o||!o&&!n)&&((Z(t)!=="body"||it(a))&&(s=Qo(t)),K(t)?(l=be(t,!0),l.x+=t.clientLeft,l.y+=t.clientTop):a&&(l.x=at(a))),{x:i.left+s.scrollLeft-l.x,y:i.top+s.scrollTop-l.y,width:i.width,height:i.height}}function nr(e){var t=new Map,n=new Set,o=[];e.forEach(function(a){t.set(a.name,a)});function r(a){n.add(a.name);var i=[].concat(a.requires||[],a.requiresIfExists||[]);i.forEach(function(s){if(!n.has(s)){var l=t.get(s);l&&r(l)}}),o.push(a)}return e.forEach(function(a){n.has(a.name)||r(a)}),o}function or(e){var t=nr(e);return bo.reduce(function(n,o){return n.concat(t.filter(function(r){return r.phase===o}))},[])}function rr(e){var t;return function(){return t||(t=new Promise(function(n){Promise.resolve().then(function(){t=void 0,n(e())})})),t}}function ar(e){var t=e.reduce(function(n,o){var r=n[o.name];return n[o.name]=r?Object.assign({},r,o,{options:Object.assign({},r.options,o.options),data:Object.assign({},r.data,o.data)}):o,n},{});return Object.keys(t).map(function(n){return t[n]})}var Rt={placement:"bottom",modifiers:[],strategy:"absolute"};function kt(){for(var e=arguments.length,t=new Array(e),n=0;n<e;n++)t[n]=arguments[n];return!t.some(function(o){return!(o&&typeof o.getBoundingClientRect=="function")})}function st(e){e===void 0&&(e={});var t=e,n=t.defaultModifiers,o=n===void 0?[]:n,r=t.defaultOptions,a=r===void 0?Rt:r;return function(i,s,l){l===void 0&&(l=a);var u={placement:"bottom",orderedModifiers:[],options:Object.assign({},Rt,a),modifiersData:{},elements:{reference:i,popper:s},attributes:{},styles:{}},f=[],v=!1,b={state:u,setOptions:function(c){var O=typeof c=="function"?c(u.options):c;m(),u.options=Object.assign({},a,u.options,O),u.scrollParents={reference:me(i)?Pe(i):i.contextElement?Pe(i.contextElement):[],popper:Pe(s)};var p=or(ar([].concat(o,u.options.modifiers)));return u.orderedModifiers=p.filter(function(E){return E.enabled}),g(),b.update()},forceUpdate:function(){if(!v){var c=u.elements,O=c.reference,p=c.popper;if(kt(O,p)){u.rects={reference:tr(O,Be(p),u.options.strategy==="fixed"),popper:nt(p)},u.reset=!1,u.placement=u.options.placement,u.orderedModifiers.forEach(function(x){return u.modifiersData[x.name]=Object.assign({},x.data)});for(var E=0;E<u.orderedModifiers.length;E++){if(u.reset===!0){u.reset=!1,E=-1;continue}var C=u.orderedModifiers[E],y=C.fn,P=C.options,A=P===void 0?{}:P,w=C.name;typeof y=="function"&&(u=y({state:u,options:A,name:w,instance:b})||u)}}}},update:rr(function(){return new Promise(function(c){b.forceUpdate(),c(u)})}),destroy:function(){m(),v=!0}};if(!kt(i,s))return b;b.setOptions(l).then(function(c){!v&&l.onFirstUpdate&&l.onFirstUpdate(c)});function g(){u.orderedModifiers.forEach(function(c){var O=c.name,p=c.options,E=p===void 0?{}:p,C=c.effect;if(typeof C=="function"){var y=C({state:u,name:O,instance:b,options:E}),P=function(){};f.push(y||P)}})}function m(){f.forEach(function(c){return c()}),f=[]}return b}}st();var ir=[Qt,nn,Yt,Gt];st({defaultModifiers:ir});var sr=[Qt,nn,Yt,Gt,Go,Fo,Xo,xo,zo],lr=st({defaultModifiers:sr});const ur=["fixed","absolute"],pr=ee({boundariesPadding:{type:Number,default:0},fallbackPlacements:{type:M(Array),default:void 0},gpuAcceleration:{type:Boolean,default:!0},offset:{type:Number,default:12},placement:{type:String,values:et,default:"bottom"},popperOptions:{type:M(Object),default:()=>({})},strategy:{type:String,values:ur,default:"absolute"}}),on=ee({...pr,id:String,style:{type:M([String,Array,Object])},className:{type:M([String,Array,Object])},effect:{type:String,default:"dark"},visible:Boolean,enterable:{type:Boolean,default:!0},pure:Boolean,focusOnShow:{type:Boolean,default:!1},trapping:{type:Boolean,default:!1},popperClass:{type:M([String,Array,Object])},popperStyle:{type:M([String,Array,Object])},referenceEl:{type:M(Object)},triggerTargetEl:{type:M(Object)},stopPopperMouseEvent:{type:Boolean,default:!0},ariaLabel:{type:String,default:void 0},virtualTriggering:Boolean,zIndex:Number}),fr={mouseenter:e=>e instanceof MouseEvent,mouseleave:e=>e instanceof MouseEvent,focus:()=>!0,blur:()=>!0,close:()=>!0},St=(e,t)=>{const{placement:n,strategy:o,popperOptions:r}=e,a={placement:n,strategy:o,...r,modifiers:dr(e)};return vr(a,t),gr(a,r==null?void 0:r.modifiers),a},cr=e=>{if(!!Te)return _t(e)};function dr(e){const{offset:t,gpuAcceleration:n,fallbackPlacements:o}=e;return[{name:"offset",options:{offset:[0,t!=null?t:12]}},{name:"preventOverflow",options:{padding:{top:2,bottom:2,left:5,right:5}}},{name:"flip",options:{padding:5,fallbackPlacements:o}},{name:"computeStyles",options:{gpuAcceleration:n}}]}function vr(e,{arrowEl:t,arrowOffset:n}){e.modifiers.push({name:"arrow",options:{element:t,padding:n!=null?n:5}})}function gr(e,t){t&&(e.modifiers=[...e.modifiers,...t!=null?t:[]])}const mr=$({name:"ElPopperContent"}),hr=$({...mr,props:on,emits:fr,setup(e,{expose:t,emit:n}){const o=e,{popperInstanceRef:r,contentRef:a,triggerRef:i,role:s}=le(Xe,void 0),l=le(ht,void 0),{nextZIndex:u}=Cn(),f=Se("popper"),v=N(),b=N("first"),g=N(),m=N();Ae(Lt,{arrowRef:g,arrowOffset:m}),l&&(l.addInputId||l.removeInputId)&&Ae(ht,{...l,addInputId:Ge,removeInputId:Ge});const c=N(o.zIndex||u()),O=N(!1);let p;const E=j(()=>cr(o.referenceEl)||d(i)),C=j(()=>[{zIndex:d(c)},o.popperStyle]),y=j(()=>[f.b(),f.is("pure",o.pure),f.is(o.effect),o.popperClass]),P=j(()=>s&&s.value==="dialog"?"false":void 0),A=({referenceEl:T,popperContentEl:R,arrowEl:k})=>{const S=St(o,{arrowEl:k,arrowOffset:d(m)});return lr(T,R,S)},w=(T=!0)=>{var R;(R=d(r))==null||R.update(),T&&(c.value=o.zIndex||u())},x=()=>{var T,R;const k={name:"eventListeners",enabled:o.visible};(R=(T=d(r))==null?void 0:T.setOptions)==null||R.call(T,S=>({...S,modifiers:[...S.modifiers||[],k]})),w(!1),o.visible&&o.focusOnShow?O.value=!0:o.visible===!1&&(O.value=!1)},I=()=>{n("focus")},_=T=>{var R;((R=T.detail)==null?void 0:R.focusReason)!=="pointer"&&(b.value="first",n("blur"))},B=T=>{o.visible&&!O.value&&(T.target&&(b.value=T.target),O.value=!0)},D=T=>{o.trapping||(T.detail.focusReason==="pointer"&&T.preventDefault(),O.value=!1)},h=()=>{O.value=!1,n("close")};return Je(()=>{let T;L(E,R=>{var k;T==null||T();const S=d(r);if((k=S==null?void 0:S.destroy)==null||k.call(S),R){const V=d(v);a.value=V,r.value=A({referenceEl:R,popperContentEl:V,arrowEl:d(g)}),T=L(()=>R.getBoundingClientRect(),()=>w(),{immediate:!0})}else r.value=void 0},{immediate:!0}),L(()=>o.triggerTargetEl,(R,k)=>{p==null||p(),p=void 0;const S=d(R||v.value),V=d(k||v.value);We(S)&&(p=L([s,()=>o.ariaLabel,P,()=>o.id],F=>{["role","aria-label","aria-modal","id"].forEach((oe,pe)=>{jt(F[pe])?S.removeAttribute(oe):S.setAttribute(oe,F[pe])})},{immediate:!0})),V!==S&&We(V)&&["role","aria-label","aria-modal","id"].forEach(F=>{V.removeAttribute(F)})},{immediate:!0}),L(()=>o.visible,x,{immediate:!0}),L(()=>St(o,{arrowEl:d(g),arrowOffset:d(m)}),R=>{var k;return(k=r.value)==null?void 0:k.setOptions(R)})}),Ke(()=>{p==null||p(),p=void 0}),t({popperContentRef:v,popperInstanceRef:r,updatePopper:w,contentStyle:C}),(T,R)=>(U(),He("div",{ref_key:"popperContentRef",ref:v,style:An(d(C)),class:Ze(d(y)),tabindex:"-1",onMouseenter:R[0]||(R[0]=k=>T.$emit("mouseenter",k)),onMouseleave:R[1]||(R[1]=k=>T.$emit("mouseleave",k))},[xe(d(Pn),{trapped:O.value,"trap-on-focus-in":!0,"focus-trap-el":v.value,"focus-start-el":b.value,onFocusAfterTrapped:I,onFocusAfterReleased:_,onFocusin:B,onFocusoutPrevented:D,onReleaseRequested:h},{default:te(()=>[se(T.$slots,"default")]),_:3},8,["trapped","focus-trap-el","focus-start-el"])],38))}});var br=ue(hr,[["__file","/home/runner/work/element-plus/element-plus/packages/components/popper/src/content.vue"]]);const yr=Nt(Xn),wr=Se("tooltip"),rn=ee({...zn,...on,appendTo:{type:M([String,Object]),default:Ht},content:{type:String,default:""},rawContent:{type:Boolean,default:!1},persistent:Boolean,ariaLabel:String,visible:{type:M(Boolean),default:null},transition:{type:String,default:`${wr.namespace.value}-fade-in-linear`},teleported:{type:Boolean,default:!0},disabled:{type:Boolean}}),an=ee({...qt,disabled:Boolean,trigger:{type:M([String,Array]),default:"hover"},triggerKeys:{type:M(Array),default:()=>[bt.enter,bt.space]}}),{useModelToggleProps:Or,useModelToggleEmits:Er,useModelToggle:Tr}=$n("visible"),Cr=ee({...Ft,...Or,...rn,...an,...Kt,showArrow:{type:Boolean,default:!0}}),Pr=[...Er,"before-show","before-hide","show","hide","open","close"],Ar=(e,t)=>xn(e)?e.includes(t):e===t,ce=(e,t,n)=>o=>{Ar(d(e),t)&&n(o)},xr=$({name:"ElTooltipTrigger"}),Rr=$({...xr,props:an,setup(e,{expose:t}){const n=e,o=Se("tooltip"),{controlled:r,id:a,open:i,onOpen:s,onClose:l,onToggle:u}=le(Ye,void 0),f=N(null),v=()=>{if(d(r)||n.disabled)return!0},b=Le(n,"trigger"),g=Y(v,ce(b,"hover",s)),m=Y(v,ce(b,"hover",l)),c=Y(v,ce(b,"click",y=>{y.button===0&&u(y)})),O=Y(v,ce(b,"focus",s)),p=Y(v,ce(b,"focus",l)),E=Y(v,ce(b,"contextmenu",y=>{y.preventDefault(),u(y)})),C=Y(v,y=>{const{code:P}=y;n.triggerKeys.includes(P)&&(y.preventDefault(),u(y))});return t({triggerRef:f}),(y,P)=>(U(),ve(d(ao),{id:d(a),"virtual-ref":y.virtualRef,open:d(i),"virtual-triggering":y.virtualTriggering,class:Ze(d(o).e("trigger")),onBlur:d(p),onClick:d(c),onContextmenu:d(E),onFocus:d(O),onMouseenter:d(g),onMouseleave:d(m),onKeydown:d(C)},{default:te(()=>[se(y.$slots,"default")]),_:3},8,["id","virtual-ref","open","virtual-triggering","class","onBlur","onClick","onContextmenu","onFocus","onMouseenter","onMouseleave","onKeydown"]))}});var kr=ue(Rr,[["__file","/home/runner/work/element-plus/element-plus/packages/components/tooltip/src/trigger.vue"]]);const Sr=$({name:"ElTooltipContent",inheritAttrs:!1}),Mr=$({...Sr,props:rn,setup(e,{expose:t}){const n=e,o=N(null),r=N(!1),{controlled:a,id:i,open:s,trigger:l,onClose:u,onOpen:f,onShow:v,onHide:b,onBeforeShow:g,onBeforeHide:m}=le(Ye,void 0),c=j(()=>n.persistent);Ke(()=>{r.value=!0});const O=j(()=>d(c)?!0:d(s)),p=j(()=>n.disabled?!1:d(s)),E=j(()=>{var h;return(h=n.style)!=null?h:{}}),C=j(()=>!d(s)),y=()=>{b()},P=()=>{if(d(a))return!0},A=Y(P,()=>{n.enterable&&d(l)==="hover"&&f()}),w=Y(P,()=>{d(l)==="hover"&&u()}),x=()=>{var h,T;(T=(h=o.value)==null?void 0:h.updatePopper)==null||T.call(h),g==null||g()},I=()=>{m==null||m()},_=()=>{v(),D=Mn(j(()=>{var h;return(h=o.value)==null?void 0:h.popperContentRef}),()=>{if(d(a))return;d(l)!=="hover"&&u()})},B=()=>{n.virtualTriggering||u()};let D;return L(()=>d(s),h=>{h||D==null||D()},{flush:"post"}),L(()=>n.content,()=>{var h,T;(T=(h=o.value)==null?void 0:h.updatePopper)==null||T.call(h)}),t({contentRef:o}),(h,T)=>(U(),ve(Sn,{disabled:!h.teleported,to:h.appendTo},[xe(kn,{name:h.transition,onAfterLeave:y,onBeforeEnter:x,onAfterEnter:_,onBeforeLeave:I},{default:te(()=>[d(O)?It((U(),ve(d(br),Dt({key:0,id:d(i),ref_key:"contentRef",ref:o},h.$attrs,{"aria-label":h.ariaLabel,"aria-hidden":d(C),"boundaries-padding":h.boundariesPadding,"fallback-placements":h.fallbackPlacements,"gpu-acceleration":h.gpuAcceleration,offset:h.offset,placement:h.placement,"popper-options":h.popperOptions,strategy:h.strategy,effect:h.effect,enterable:h.enterable,pure:h.pure,"popper-class":h.popperClass,"popper-style":[h.popperStyle,d(E)],"reference-el":h.referenceEl,"trigger-target-el":h.triggerTargetEl,visible:d(p),"z-index":h.zIndex,onMouseenter:d(A),onMouseleave:d(w),onBlur:B,onClose:d(u)}),{default:te(()=>[de(" Workaround bug #6378 "),r.value?de("v-if",!0):se(h.$slots,"default",{key:0})]),_:3},16,["id","aria-label","aria-hidden","boundaries-padding","fallback-placements","gpu-acceleration","offset","placement","popper-options","strategy","effect","enterable","pure","popper-class","popper-style","reference-el","trigger-target-el","visible","z-index","onMouseenter","onMouseleave","onClose"])),[[Rn,d(p)]]):de("v-if",!0)]),_:3},8,["name"])],8,["disabled","to"]))}});var Br=ue(Mr,[["__file","/home/runner/work/element-plus/element-plus/packages/components/tooltip/src/content.vue"]]);const Ir=["innerHTML"],_r={key:1},jr=$({name:"ElTooltip"}),Dr=$({...jr,props:Cr,emits:Pr,setup(e,{expose:t,emit:n}){const o=e;Kn();const r=Bn(),a=N(),i=N(),s=()=>{var p;const E=d(a);E&&((p=E.popperInstanceRef)==null||p.update())},l=N(!1),u=N(),{show:f,hide:v,hasUpdateHandler:b}=Tr({indicator:l,toggleReason:u}),{onOpen:g,onClose:m}=qn({showAfter:Le(o,"showAfter"),hideAfter:Le(o,"hideAfter"),open:f,close:v}),c=j(()=>Bt(o.visible)&&!b.value);Ae(Ye,{controlled:c,id:r,open:In(l),trigger:Le(o,"trigger"),onOpen:p=>{g(p)},onClose:p=>{m(p)},onToggle:p=>{d(l)?m(p):g(p)},onShow:()=>{n("show",u.value)},onHide:()=>{n("hide",u.value)},onBeforeShow:()=>{n("before-show",u.value)},onBeforeHide:()=>{n("before-hide",u.value)},updatePopper:s}),L(()=>o.disabled,p=>{p&&l.value&&(l.value=!1)});const O=()=>{var p,E;const C=(E=(p=i.value)==null?void 0:p.contentRef)==null?void 0:E.popperContentRef;return C&&C.contains(document.activeElement)};return _n(()=>l.value&&v()),t({popperRef:a,contentRef:i,isFocusInsideContent:O,updatePopper:s,onOpen:g,onClose:m,hide:v}),(p,E)=>(U(),ve(d(yr),{ref_key:"popperRef",ref:a,role:p.role},{default:te(()=>[xe(kr,{disabled:p.disabled,trigger:p.trigger,"trigger-keys":p.triggerKeys,"virtual-ref":p.virtualRef,"virtual-triggering":p.virtualTriggering},{default:te(()=>[p.$slots.default?se(p.$slots,"default",{key:0}):de("v-if",!0)]),_:3},8,["disabled","trigger","trigger-keys","virtual-ref","virtual-triggering"]),xe(Br,{ref_key:"contentRef",ref:i,"aria-label":p.ariaLabel,"boundaries-padding":p.boundariesPadding,content:p.content,disabled:p.disabled,effect:p.effect,enterable:p.enterable,"fallback-placements":p.fallbackPlacements,"hide-after":p.hideAfter,"gpu-acceleration":p.gpuAcceleration,offset:p.offset,persistent:p.persistent,"popper-class":p.popperClass,"popper-style":p.popperStyle,placement:p.placement,"popper-options":p.popperOptions,pure:p.pure,"raw-content":p.rawContent,"reference-el":p.referenceEl,"trigger-target-el":p.triggerTargetEl,"show-after":p.showAfter,strategy:p.strategy,teleported:p.teleported,transition:p.transition,"virtual-triggering":p.virtualTriggering,"z-index":p.zIndex,"append-to":p.appendTo},{default:te(()=>[se(p.$slots,"content",{},()=>[p.rawContent?(U(),He("span",{key:0,innerHTML:p.content},null,8,Ir)):(U(),He("span",_r,jn(p.content),1))]),p.showArrow?(U(),ve(d(eo),{key:0,"arrow-offset":p.arrowOffset},null,8,["arrow-offset"])):de("v-if",!0)]),_:3},8,["aria-label","boundaries-padding","content","disabled","effect","enterable","fallback-placements","hide-after","gpu-acceleration","offset","persistent","popper-class","popper-style","placement","popper-options","pure","raw-content","reference-el","trigger-target-el","show-after","strategy","teleported","transition","virtual-triggering","z-index","append-to"])]),_:3},8,["role"]))}});var Nr=ue(Dr,[["__file","/home/runner/work/element-plus/element-plus/packages/components/tooltip/src/tooltip.vue"]]);const Wr=Nt(Nr);export{Wr as E,no as O,et as a,an as b,Y as c,Hr as d,rn as u,$r as w};
