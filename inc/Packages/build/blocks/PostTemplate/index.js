(()=>{"use strict";var e,t={385:(e,t,r)=>{const o=window.wp.blocks,s=window.wp.primitives;var n=r(848);const l=(0,n.jsx)(s.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",children:(0,n.jsx)(s.Path,{d:"M18 5.5H6a.5.5 0 00-.5.5v3h13V6a.5.5 0 00-.5-.5zm.5 5H10v8h8a.5.5 0 00.5-.5v-7.5zm-10 0h-3V18a.5.5 0 00.5.5h2.5v-8zM6 4h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2z"})}),a=JSON.parse('{"UU":"brandy/post-template"}');var i=r(609);const c=window.wp.blockEditor,p=window.wp.components,u=window.wp.coreData,d=window.wp.data,m=window.wp.element,v=window.wp.i18n,y=(0,n.jsx)(s.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg",children:(0,n.jsx)(s.Path,{d:"M4 4v1.5h16V4H4zm8 8.5h8V11h-8v1.5zM4 20h16v-1.5H4V20zm4-8c0-1.1-.9-2-2-2s-2 .9-2 2 .9 2 2 2 2-.9 2-2z"})}),f=(0,n.jsx)(s.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",children:(0,n.jsx)(s.Path,{d:"m3 5c0-1.10457.89543-2 2-2h13.5c1.1046 0 2 .89543 2 2v13.5c0 1.1046-.8954 2-2 2h-13.5c-1.10457 0-2-.8954-2-2zm2-.5h6v6.5h-6.5v-6c0-.27614.22386-.5.5-.5zm-.5 8v6c0 .2761.22386.5.5.5h6v-6.5zm8 0v6.5h6c.2761 0 .5-.2239.5-.5v-6zm0-8v6.5h6.5v-6c0-.27614-.2239-.5-.5-.5z",fillRule:"evenodd",clipRule:"evenodd"})});function w(e){const{setAttributes:t,attributes:r}=e,{layout:o}=r,s=e=>t({layout:{...o,...e}}),n=[{icon:y,title:(0,v._x)("List view","Post template block display setting"),onClick:()=>s({type:"default"}),isActive:"default"===o?.type},{icon:f,title:(0,v._x)("Grid view","Post template block display setting"),onClick:()=>s({type:"grid"}),isActive:"default"!==o?.type}];return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(p.ToolbarGroup,{controls:n}))}function b(e){var t,r,o="";if("string"==typeof e||"number"==typeof e)o+=e;else if("object"==typeof e)if(Array.isArray(e)){var s=e.length;for(t=0;t<s;t++)e[t]&&(r=b(e[t]))&&(o&&(o+=" "),o+=r)}else for(r in e)e[r]&&(o&&(o+=" "),o+=r);return o}const g=function(){for(var e,t,r=0,o="",s=arguments.length;r<s;r++)(e=arguments[r])&&(t=b(e))&&(o&&(o+=" "),o+=t);return o},x=[["core/post-featured-image",{isLink:!0,style:{border:{radius:"10px"},spacing:{margin:{bottom:"1rem"}}}}],["core/post-date",{textColor:"brandy-secondary-text",fontSize:"small",textAlign:"center",style:{spacing:{margin:{top:"0",bottom:"6px",left:"0",right:"0"}}}}],["core/post-title",{textColor:"brandy-primary-text",textAlign:"center",level:4,isLink:!0,className:"brandy-text-ellipsis-2",style:{elements:{link:{color:{text:"var:preset|color|brandy-primary-text"}}},spacing:{margin:{top:"0",bottom:"0",left:"0",right:"0"}}}}]];function h({classList:e}){const t=(0,c.useInnerBlocksProps)({className:g("wp-block-post",e)},{template:x,__unstableDisableLayoutClassNames:!0});return(0,i.createElement)("li",{...t})}const k=(0,m.memo)((function({blocks:e,blockContextId:t,classList:r,isHidden:o,setActiveBlockContextId:s}){const n=(0,c.__experimentalUseBlockPreview)({blocks:e,props:{className:g("wp-block-post",r)}}),l=()=>{s(t)},a={display:o?"none":void 0};return(0,i.createElement)("li",{...n,tabIndex:0,role:"button",onClick:l,onKeyPress:l,style:a})}));(0,o.registerBlockType)(a.UU,{edit:function(e){var t;const{attributes:r,clientId:o,setAttributes:s,__unstableLayoutClassNames:n,context:l}=e,{layout:a}=r,y=(0,c.useBlockProps)({className:g(n,{[`columns-${null!==(t=a?.columnCount)&&void 0!==t?t:3}`]:"default"!==a?.type&&a?.columnCount})}),[f,b]=(0,m.useState)(),{posts:x,blocks:_}=(0,d.useSelect)((e=>{const{getEntityRecords:t}=e(u.store),{getBlocks:r}=e(c.store),s={offset:l?.query?.offset||0,order:l?.query?.order||"asc",orderby:l?.query?.orderBy||"title"};return l?.query?.perPage&&(s.per_page=l?.query?.perPage),l?.query?.categoryIds&&(s.categories=l?.query?.categoryIds),l?.query?.tagIds&&(s.tags=l?.query?.tagIds),l?.query?.exclude&&(s.exclude=l?.query?.exclude),{posts:t("postType","post",{...s}),blocks:r(o)}}),[l.query]),E=(0,m.useMemo)((()=>x?.map((e=>{var t;return{postType:e.type,postId:e.id,classList:null!==(t=e.class_list)&&void 0!==t?t:""}}))),[x]);return x?x.length?(0,i.createElement)(i.Fragment,null,(0,i.createElement)(c.BlockControls,null,(0,i.createElement)(w,{attributes:r,setAttributes:s,clientId:o})),(0,i.createElement)("ul",{...y},E&&E.map((e=>(0,i.createElement)(c.BlockContextProvider,{key:e.postId,value:e},e.postId===(f||E[0]?.postId)?(0,i.createElement)(h,{classList:e.classList}):null,(0,i.createElement)(k,{blocks:_,blockContextId:e.postId,classList:e.classList,setActiveBlockContextId:b,isHidden:e.postId===(f||E[0]?.postId)})))))):(0,i.createElement)("p",{...y}," ",(0,v.__)("No results found.")):(0,i.createElement)("p",{...y},(0,i.createElement)(p.Spinner,null))},save:function(){return(0,i.createElement)(c.InnerBlocks.Content,null)},icon:l})},20:(e,t,r)=>{var o=r(609),s=Symbol.for("react.element"),n=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),l=o.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,a={key:!0,ref:!0,__self:!0,__source:!0};t.jsx=function(e,t,r){var o,i={},c=null,p=null;for(o in void 0!==r&&(c=""+r),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(p=t.ref),t)n.call(t,o)&&!a.hasOwnProperty(o)&&(i[o]=t[o]);if(e&&e.defaultProps)for(o in t=e.defaultProps)void 0===i[o]&&(i[o]=t[o]);return{$$typeof:s,type:e,key:c,ref:p,props:i,_owner:l.current}}},848:(e,t,r)=>{e.exports=r(20)},609:e=>{e.exports=window.React}},r={};function o(e){var s=r[e];if(void 0!==s)return s.exports;var n=r[e]={exports:{}};return t[e](n,n.exports,o),n.exports}o.m=t,e=[],o.O=(t,r,s,n)=>{if(!r){var l=1/0;for(p=0;p<e.length;p++){r=e[p][0],s=e[p][1],n=e[p][2];for(var a=!0,i=0;i<r.length;i++)(!1&n||l>=n)&&Object.keys(o.O).every((e=>o.O[e](r[i])))?r.splice(i--,1):(a=!1,n<l&&(l=n));if(a){e.splice(p--,1);var c=s();void 0!==c&&(t=c)}}return t}n=n||0;for(var p=e.length;p>0&&e[p-1][2]>n;p--)e[p]=e[p-1];e[p]=[r,s,n]},o.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={633:0,669:0};o.O.j=t=>0===e[t];var t=(t,r)=>{var s,n,l=r[0],a=r[1],i=r[2],c=0;if(l.some((t=>0!==e[t]))){for(s in a)o.o(a,s)&&(o.m[s]=a[s]);if(i)var p=i(o)}for(t&&t(r);c<l.length;c++)n=l[c],o.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return o.O(p)},r=self.webpackChunkbrandy_blocks=self.webpackChunkbrandy_blocks||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})();var s=o.O(void 0,[669],(()=>o(385)));s=o.O(s)})();