(()=>{"use strict";var e={20:(e,t,n)=>{var r=n(609),o=Symbol.for("react.element"),i=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),a=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,s={key:!0,ref:!0,__self:!0,__source:!0};t.jsx=function(e,t,n){var r,l={},c=null,p=null;for(r in void 0!==n&&(c=""+n),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(p=t.ref),t)i.call(t,r)&&!s.hasOwnProperty(r)&&(l[r]=t[r]);if(e&&e.defaultProps)for(r in t=e.defaultProps)void 0===l[r]&&(l[r]=t[r]);return{$$typeof:o,type:e,key:c,ref:p,props:l,_owner:a.current}}},848:(e,t,n)=>{e.exports=n(20)},609:e=>{e.exports=window.React}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,n),i.exports}const r=window.wp.blocks,o=window.wp.primitives;var i=n(848);const a=(0,i.jsx)(o.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24",children:(0,i.jsx)(o.Path,{d:"m9.99609 14v-.2251l.00391.0001v6.225h1.5v-14.5h2.5v14.5h1.5v-14.5h3v-1.5h-8.50391c-2.76142 0-5 2.23858-5 5 0 2.7614 2.23858 5 5 5z"})}),s=JSON.parse('{"UU":"brandy/testimonial-description"}');var l=n(609);const c=window.wp.blockEditor,p=window.wp.components;function u({context:e,clientId:t,attributes:n,setAttributes:r}){var o;return(0,l.createElement)(c.BlockControls,{group:"block"},(0,l.createElement)(p.ToolbarGroup,null,(0,l.createElement)(p.Button,{isPrimary:!0,onClick:()=>{window.dispatchEvent(new CustomEvent("brandySyncTestimonialsLayout",{detail:{rootId:e.rootId,singleId:e.singleTestimonialId,syncBlocks:["description"],blockId:t}}))}},"Sync all testinomial description")),(0,l.createElement)(c.AlignmentControl,{value:null!==(o=n.align)&&void 0!==o?o:"left",onChange:e=>r({align:e})}))}function d(e){const{isSave:t=!1,attributes:n,setAttributes:r}=e,{align:o}=n,i={className:o?`has-text-align-${o}`:""},a=t?c.useBlockProps.save(i):(0,c.useBlockProps)(i);return(0,l.createElement)("div",{...a},t?(0,l.createElement)(c.RichText.Content,{tagName:"p",value:n.description}):(0,l.createElement)(c.RichText,{tagName:"p",value:n.description,onChange:e=>r({description:e}),className:"brandy-testimonial__description"}))}(0,r.registerBlockType)(s.UU,{edit:function(e){return(0,l.createElement)(l.Fragment,null,(0,l.createElement)(u,{...e}),(0,l.createElement)(d,{...e}))},save:function(e){return(0,l.createElement)(d,{...e,isSave:!0})},icon:a})})();