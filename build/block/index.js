(()=>{"use strict";var e={n:r=>{var a=r&&r.__esModule?()=>r.default:()=>r;return e.d(a,{a}),a},d:(r,a)=>{for(var t in a)e.o(a,t)&&!e.o(r,t)&&Object.defineProperty(r,t,{enumerable:!0,get:a[t]})},o:(e,r)=>Object.prototype.hasOwnProperty.call(e,r)};const r=window.wp.blocks,a=window.wp.i18n,t=window.wp.element,o=window.wp.components,s=window.wp.blockEditor,l=window.wp.serverSideRender;var n=e.n(l);const c=window.ReactJSXRuntime,d=JSON.parse('{"UU":"fau-degree-program/shares"}'),i=(0,c.jsx)("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 576 512",children:(0,c.jsx)("path",{d:"M304 240l0-223.4c0-9 7-16.6 16-16.6C443.7 0 544 100.3 544 224c0 9-7.6 16-16.6 16L304 240zM32 272C32 150.7 122.1 50.3 239 34.3c9.2-1.3 17 6.1 17 15.4L256 288 412.5 444.5c6.7 6.7 6.2 17.7-1.5 23.1C371.8 495.6 323.8 512 272 512C139.5 512 32 404.6 32 272zm526.4 16c9.3 0 16.6 7.8 15.4 17c-7.7 55.9-34.6 105.6-73.9 142.3c-6 5.6-15.4 5.2-21.2-.7L320 288l238.4 0z"})});(0,r.registerBlockType)(d.UU,{icon:i,edit:e=>{const{attributes:r,setAttributes:l}=e,{selectedDegree:d,selectedSubject:i,format:u,showPercent:h,showTitle:p}=r,[w,g]=(0,t.useState)([]),[_,b]=(0,t.useState)([]),[m]=(0,t.useState)(["chart"]),[x]=(0,t.useState)(!0),[f]=(0,t.useState)(!1);return(0,t.useEffect)((()=>{window.sharesBlockData&&(g(window.sharesBlockData.degreeOptions||[]),b(window.sharesBlockData.subjectOptions||[]))}),[]),(0,c.jsxs)("div",{...(0,s.useBlockProps)(),children:[(0,c.jsx)(s.InspectorControls,{children:(0,c.jsxs)(o.PanelBody,{children:[(0,c.jsx)(o.SelectControl,{label:(0,a.__)("Degree","fau-degree-program-shares"),value:d,options:w.map((e=>({value:e.value,label:e.label}))),onChange:e=>l({selectedDegree:e})}),(0,c.jsx)(o.SelectControl,{label:(0,a.__)("Subject","fau-degree-program-shares"),value:i,options:_.map((e=>({value:e.value,label:e.label}))),onChange:e=>l({selectedSubject:e})}),(0,c.jsxs)(o.__experimentalRadioGroup,{label:(0,a.__)("Format","fau-degree-program-shares"),onChange:e=>{l({format:e})},checked:u,children:[(0,c.jsx)(o.__experimentalRadio,{__next40pxDefaultSize:!0,value:"chart",children:(0,a.__)("Chart","fau-degree-program-shares")}),(0,c.jsx)(o.__experimentalRadio,{__next40pxDefaultSize:!0,value:"table",children:(0,a.__)("Table","fau-degree-program-shares")})]}),"chart"===u&&(0,c.jsx)(o.ToggleControl,{label:(0,a.__)("Show Percent Values","fau-degree-program-shares"),checked:h,onChange:e=>{l({showPercent:e})}}),(0,c.jsx)(o.ToggleControl,{label:(0,a.__)("Show Title","fau-degree-program-shares"),checked:p,onChange:e=>{l({showTitle:e})}})]})}),(0,c.jsx)(n(),{block:"fau-degree-program/shares",attributes:r})]})},save:function(){return null}})})();