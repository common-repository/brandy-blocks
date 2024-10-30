(()=>{"use strict";var e,t={860:()=>{const e=window.React,{InspectorControls:t}=wp.blockEditor,{PanelBody:a,CheckboxControl:l}=wp.components,{__}=wp.i18n,{createHigherOrderComponent:r}=wp.compose,{addFilter:n}=wp.hooks,o=React.createElement("svg",{width:"19",height:"14",viewBox:"0 0 19 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},React.createElement("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M18 7.00017C16.5151 10.0655 12.7959 13 9.4998 13C6.20371 13 2.48449 10.0655 1 6.99981",stroke:"#C6D4E1","stroke-width":"1.2","stroke-linecap":"round","stroke-linejoin":"round"}),React.createElement("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M18 7.00017C16.5151 3.93484 12.7965 1 9.50039 1C6.2043 1 2.48449 3.93412 1 6.99981",stroke:"#C6D4E1","stroke-width":"1.2","stroke-linecap":"round","stroke-linejoin":"round"}),React.createElement("path",{d:"M12.05 7C12.05 8.42016 10.9083 9.57143 9.5 9.57143C8.09167 9.57143 6.95 8.42016 6.95 7C6.95 5.57984 8.09167 4.42857 9.5 4.42857C10.9083 4.42857 12.05 5.57984 12.05 7Z",stroke:"#C6D4E1","stroke-width":"1.2","stroke-linecap":"round","stroke-linejoin":"round"}));n("blocks.registerBlockType","BrandyConditionBlocks/visibility/attributes",(e=>(null==e.attributes||(e.attributes=Object.assign(e.attributes,{hideLoggedIn:{type:"boolean",default:!1},hideLoggedOut:{type:"boolean",default:!1},hideRoles:{type:"array",default:[]},hideBrowsers:{type:"array",default:[]},hideDays:{type:"array",default:[]}})),e)));const i=r((r=>n=>{var i,s,d;const{attributes:c,setAttributes:b,isSelected:y}=n,{hideLoggedIn:h,hideLoggedOut:u}=c,k=null!==(i=c.hideRoles)&&void 0!==i?i:[],m=null!==(s=c.hideBrowsers)&&void 0!==s?s:[],p=null!==(d=c.hideDays)&&void 0!==d?d:[],v=brandyBlocksData.roles,g=brandyBlocksData.browsers,w=brandyBlocksData.currentUserRole,C=brandyBlocksData.currentBrowser,E=brandyBlocksData.currentDay,f=h&&brandyBlocksData.isUserLoggedIn||u&&!brandyBlocksData.isUserLoggedIn||k.includes(w)||m.includes(C)||p.includes(E),B=h||u||k.length>0||m.length>0||p.length>0?f?"brandy-conditional-visibility-blocks brandy-hidden-block":"brandy-conditional-visibility-blocks":"";return(0,e.createElement)(e.Fragment,null,(0,e.createElement)(r,{...n,className:B}),y&&(0,e.createElement)(t,null,(0,e.createElement)(a,{title:(0,e.createElement)(e.Fragment,null,o," ",__("Visibility Conditions","brandy-blocks")),initialOpen:!1,className:"brandy-visibility-panel"},(0,e.createElement)("div",{className:"brandy-visibility-controls-wrapper"},(0,e.createElement)("div",{className:"brandy-visibility-controls-label"},__("User State","brandy-blocks")),(0,e.createElement)(l,{label:__("Hide when logged in"),checked:h,onChange:e=>b({hideLoggedIn:e}),className:"brandy-user-state-checkbox"}),(0,e.createElement)(l,{label:__("Hide when logged out"),checked:u,onChange:e=>b({hideLoggedOut:e}),className:"brandy-user-state-checkbox"})),(0,e.createElement)("div",{className:"brandy-visibility-controls-wrapper"},(0,e.createElement)("div",{className:"brandy-visibility-controls-label"},__("Choose roles to hide from","brandy-blocks")),v.map((t=>(0,e.createElement)(l,{key:t.value,label:t.label,checked:k.includes(t.value),onChange:()=>(e=>{const t=k.includes(e)?k.filter((t=>t!==e)):[...k,e];b({hideRoles:t})})(t.value),className:"brandy-role-checkbox"})))),(0,e.createElement)("div",{className:"brandy-visibility-controls-wrapper"},(0,e.createElement)("div",{className:"brandy-visibility-controls-label"},__("Choose browsers to hide","brandy-blocks")),g.map((t=>(0,e.createElement)(l,{key:t,label:t,checked:m.includes(t),onChange:e=>{const a=e?[...m,t]:m.filter((e=>e!==t));b({hideBrowsers:a})},className:"brandy-browser-checkbox"})))),(0,e.createElement)("div",{className:"brandy-visibility-controls-wrapper"},(0,e.createElement)("div",{className:"brandy-visibility-controls-label"},__("Choose days to hide","brandy-blocks")),["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"].map((t=>(0,e.createElement)(l,{key:t,label:t,checked:p.includes(t),onChange:e=>{const a=e?[...p,t]:p.filter((e=>e!==t));b({hideDays:a})},className:"brandy-day-checkbox"})))))))}),"withConditionalVisibility");n("editor.BlockEdit","BrandyConditionBlocks/VisibilityEditor",i),n("editor.BlockListBlock","BrandyConditionBlocks/editor/blockClasses",i)}},a={};function l(e){var r=a[e];if(void 0!==r)return r.exports;var n=a[e]={exports:{}};return t[e](n,n.exports,l),n.exports}l.m=t,e=[],l.O=(t,a,r,n)=>{if(!a){var o=1/0;for(c=0;c<e.length;c++){a=e[c][0],r=e[c][1],n=e[c][2];for(var i=!0,s=0;s<a.length;s++)(!1&n||o>=n)&&Object.keys(l.O).every((e=>l.O[e](a[s])))?a.splice(s--,1):(i=!1,n<o&&(o=n));if(i){e.splice(c--,1);var d=r();void 0!==d&&(t=d)}}return t}n=n||0;for(var c=e.length;c>0&&e[c-1][2]>n;c--)e[c]=e[c-1];e[c]=[a,r,n]},l.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={311:0,519:0};l.O.j=t=>0===e[t];var t=(t,a)=>{var r,n,o=a[0],i=a[1],s=a[2],d=0;if(o.some((t=>0!==e[t]))){for(r in i)l.o(i,r)&&(l.m[r]=i[r]);if(s)var c=s(l)}for(t&&t(a);d<o.length;d++)n=o[d],l.o(e,n)&&e[n]&&e[n][0](),e[n]=0;return l.O(c)},a=self.webpackChunkbrandy_blocks=self.webpackChunkbrandy_blocks||[];a.forEach(t.bind(null,0)),a.push=t.bind(null,a.push.bind(a))})();var r=l.O(void 0,[519],(()=>l(860)));r=l.O(r)})();