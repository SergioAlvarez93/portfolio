"use strict";(globalThis.itsecWebpackJsonP=globalThis.itsecWebpackJsonP||[]).push([[8006],{88493:(e,t,a)=>{a.d(t,{Z:()=>K});var r=a(6293),n=a(12614),l=a(95122),i=a(48015),c=a(71930),o=a(31600),s=a(97157),m=a(52117),u=a(64893);const d=(0,m.Z)(u.Modal,{target:"e196uad513"})("max-width:600px;min-width:480px;.components-modal__header{padding:0 1.25rem;& button:hover{color:",(({theme:e})=>e.colors.secondary.darker20),";}& button:focus{box-shadow:0 0 0 2px ",(({theme:e})=>e.colors.primary.base),";}}.components-modal__content{padding:0 1.25rem 2rem;}.components-modal__header-heading{font-size:",(({theme:e})=>e.sizes.text.large),";}"),p=(0,m.Z)(u.Flex,{target:"e196uad512"})({name:"y8n2z8",styles:"margin-top:2.5rem"}),g=(0,m.Z)(c.Tg,{target:"e196uad511"})({name:"18wo122",styles:"display:flex;align-items:center;gap:1.5rem;background:linear-gradient(94deg, #292929 66.78%, #8645D1 141.05%);margin:1rem 0 0;padding:1.75rem"}),h=(0,m.Z)("div",{target:"e196uad510"})({name:"4zk4ri",styles:"display:flex;flex-direction:column;gap:1rem"}),y=(0,m.Z)("div",{target:"e196uad59"})({name:"zed147",styles:"display:flex;flex-direction:column;gap:2.5rem"}),f=(0,m.Z)(c.Tg,{target:"e196uad58"})({name:"gljbzz",styles:"display:flex;justify-content:center;padding:1.25rem;background:linear-gradient(94deg, #292929 66.78%, #8645D1 141.05%)"}),E=(0,m.Z)("div",{target:"e196uad57"})({name:"123v8di",styles:"display:flex;gap:1.25rem;align-items:center"}),_=(0,m.Z)("div",{target:"e196uad56"})({name:"15idi1d",styles:"display:flex;flex-direction:column;gap:0.75rem"}),w=(0,m.Z)("div",{target:"e196uad55"})({name:"zed147",styles:"display:flex;flex-direction:column;gap:2.5rem"}),x=(0,m.Z)("div",{target:"e196uad54"})({name:"swulno",styles:"width:200px;height:168px"}),b=(0,m.Z)("img",{target:"e196uad53"})({name:"swulno",styles:"width:200px;height:168px"}),v=(0,m.Z)(c.xv,{target:"e196uad52"})("max-width:400px;margin:0 auto;& a{color:",(({theme:e})=>e.colors.primary.base),";}"),k=(0,m.Z)("div",{target:"e196uad51"})({name:"19us1vy",styles:"padding:60% 0 0 0;position:relative;width:552px"}),I=(0,m.Z)("iframe",{target:"e196uad50"})({name:"vjiodz",styles:"position:absolute;top:0;left:0;width:552px;height:100%"});var S=a(60976);function N({installType:e}){const t="free"===e?(0,l.__)("iThemes Security Free is now Solid Security Basic","better-wp-security"):(0,l.__)("iThemes Security Pro is now Solid Security Pro","better-wp-security");return(0,r.createElement)(g,{variant:"dark"},(0,r.createElement)("div",null,(0,r.createElement)(S.iO,null)),(0,r.createElement)(h,null,(0,r.createElement)(c.xv,{size:c.yH.HUGE,variant:"white",text:t})))}function L({installType:e}){return(0,r.createElement)(N,{installType:e})}const O=a.p+"388febe5c99ff56cba7e.png",P=a.p+"20ba44d1ff0f0e29ccb0.png",T=a.p+"ddcd850544136c74ba67.png",C=a.p+"95e385e1bfaef2d6c09c.png";function z({heading:e,description:t,thumbnail:a}){return(0,r.createElement)(E,null,(0,r.createElement)(x,null,a),(0,r.createElement)(_,null,(0,r.createElement)(c.xv,{weight:c.fs.HEAVY,size:c.yH.LARGE,text:e}),(0,r.createElement)(c.xv,{variant:c.rK.MUTED,text:t})))}function Z({features:e}){return(0,r.createElement)(w,null,e.map((e=>(0,r.createElement)(z,{key:e.heading,thumbnail:e.thumbnail,heading:e.heading,description:e.description}))))}function D({installType:e}){return(0,r.createElement)(y,null,(0,r.createElement)(G,{text:(0,l.__)("Take advantage of these new features","better-wp-security")}),(0,r.createElement)(Z,{features:[{heading:(0,l.__)("Clear, Concise User Interface","better-wp-security"),description:(0,l.__)("Quickly view critical data including current bans, lockouts, threats blocked, site scan results, user security profiles, and vulnerable software.","better-wp-security"),thumbnail:(0,r.createElement)(b,{src:O,alt:""})}]}),"free"===e?(0,r.createElement)(React.Fragment,null,(0,r.createElement)(Z,{features:[{heading:(0,l.__)("Clear Visibility Into Firewall Protection ","better-wp-security"),description:(0,l.__)("Gain instant insight into the attacks prevented by your firewall with a new, easy-to-read screen. ","better-wp-security"),thumbnail:(0,r.createElement)(b,{src:P,alt:""})}]}),(0,r.createElement)(v,{align:"center",variant:c.rK.MUTED,text:(0,r.createInterpolateElement)((0,l.__)("<a>Upgrade to Solid Security Pro</a> for Patchstack integration to fix vulnerabilities and prevent attacks when your attention is elsewhere. ","better-wp-security"),{a:(0,r.createElement)("a",{href:"https://go.solidwp.com/welcome-upgrade-security-pro-patchstack"})})})):(0,r.createElement)(Z,{features:[{heading:(0,l.__)("Powerful Firewall Capabilities","better-wp-security"),description:(0,l.__)("Solid Security can automatically add block rules when it identifies suspicious behavior, but you may add your own rules too.","better-wp-security"),thumbnail:(0,r.createElement)(b,{src:P,alt:""})}]}))}function U({installType:e}){const{hasPatchstack:t}=(0,i.useSelect)((e=>({hasPatchstack:e(o.coreStore).hasPatchstack()})),[]);return(0,r.createElement)(y,null,(0,r.createElement)(G,{text:(0,l.__)("Features continued…","better-wp-security")}),(0,r.createElement)(Z,{features:[{heading:(0,l.__)("New Site Scan Panel ","better-wp-security"),description:(0,l.__)("View all risks in one place. Any trouble surfacing in Google Safe Browsing, 2FA logins, or weak passwords are posted here.","better-wp-security"),thumbnail:(0,r.createElement)(b,{src:T,alt:""})},{heading:(0,l.__)("User Security Panel ","better-wp-security"),description:(0,l.__)("Quickly apply and manage a uniform security policy for all your users.","better-wp-security"),thumbnail:(0,r.createElement)(b,{src:C,alt:""})}]}),"free"===e&&(0,r.createElement)(v,{align:"center",variant:c.rK.MUTED,text:(0,r.createInterpolateElement)((0,l.__)("Want more powerful features? <a>Check out Solid Suite</a>. ","better-wp-security"),{a:(0,r.createElement)("a",{href:"https://go.solidwp.com/check-out-solid-suite"})})}),"pro"===e&&!t&&(0,r.createElement)(v,{align:"center",variant:c.rK.MUTED,text:(0,r.createInterpolateElement)((0,l.__)("Want to reduce your WordPress site’s risk to nearly zero? <a>Upgrade to Solid Security Pro with Patchstack</a>.","better-wp-security"),{a:(0,r.createElement)("a",{href:"https://go.solidwp.com/upgrade-virtual-patching"})})}))}const W=a.p+"4f7e43f7ec93cb902ce1.png";function F({installType:e}){const[t,a]=(0,r.useState)(!1);return(0,r.createElement)(y,null,(0,r.createElement)(G,{text:(0,l.__)("Learn more about SolidWP!","better-wp-security")}),!t&&(0,r.createElement)("input",{type:"image",src:W,alt:(0,l.__)("Click here to watch a video introducing SolidWP","better-wp-security"),onClick:()=>{a(!0)}}),t&&(0,r.createElement)(k,null,(0,r.createElement)("div",null,(0,r.createElement)(I,{src:"https://player.vimeo.com/video/863249227?h=deb6ff1117&autoplay=1&title=0&byline=0&portrait=0",allow:"autoplay; fullscreen; picture-in-picture",allowFullScreen:!0,title:"Welcome to SolidWP"}))),"free"===e?(0,r.createElement)(v,{align:"center",variant:c.rK.MUTED,text:(0,r.createInterpolateElement)((0,l.__)("Learn more about the rebrand <a>here</a>.","better-wp-security"),{a:(0,r.createElement)("a",{href:"https://go.solidwp.com/security-free-learn-more-solidwp"})})}):(0,r.createElement)(v,{align:"center",variant:c.rK.MUTED,text:(0,r.createInterpolateElement)((0,l.__)("Learn more about the rebrand <a>here</a>.","better-wp-security"),{a:(0,r.createElement)("a",{href:"https://go.solidwp.com/security-pro-learn-more-solidwp"})})}))}function G({text:e}){return(0,r.createElement)(f,{variant:"dark"},(0,r.createElement)(c.xv,{size:c.yH.HUGE,variant:"white",text:e}))}var M=a(14776);const A=()=>(0,r.createElement)(M.SVG,{width:"8",height:"8",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,r.createElement)(M.Circle,{cx:"4",cy:"4",r:"4"}));function H({currentPage:e,numberOfPages:t,setCurrentPage:a,onClose:n}){return(0,r.createElement)(p,{expand:!1,direction:"column",gap:"2.5rem",align:"center"},e<t-1&&(0,r.createElement)(c.zx,{variant:"primary",onClick:()=>{a(e+1)},text:(0,l.__)("Next","better-wp-security")}),e===t-1&&(0,r.createElement)(c.zx,{variant:"primary",onClick:n,text:(0,l.__)("Done","better-wp-security")}),(0,r.createElement)("ul",{className:"components-guide__page-control","aria-label":(0,l.__)("Guide controls","better-wp-security")},Array.from({length:t}).map(((n,i)=>(0,r.createElement)("li",{key:i,"aria-current":i===e?"step":void 0},(0,r.createElement)(c.zx,{variant:"link",key:i,icon:(0,r.createElement)(A,null),"aria-label":(0,l.sprintf)((0,l.__)("Page %1$d of %2$d","better-wp-security"),i+1,t),onClick:()=>a(i)}))))))}function K({onClose:e}){const[t,a]=(0,r.useState)(0),[m,u]=(0,r.useState)(!1),{installType:p,isDismissed:g}=(0,i.useSelect)((e=>({installType:e(o.coreStore).getInstallType(),isDismissed:e(s.store).isNoticeDismissed("welcome-solidwp")})),[]),{doNoticeAction:h}=(0,i.useDispatch)(s.store);if(m||g)return null;const y=()=>{e?.(),h("welcome-solidwp","dismiss"),u(!0)},f="free"===p?(0,l.__)("Welcome to Solid Security Basic","better-wp-security"):(0,l.__)("Welcome to Solid Security Pro","better-wp-security");return(0,r.createElement)(n.a,{theme:c.U1},(0,r.createElement)(d,{title:f,onRequestClose:y},(0,r.createElement)("div",null,0===t&&(0,r.createElement)(L,{installType:p}),1===t&&(0,r.createElement)(D,{installType:p}),2===t&&(0,r.createElement)(U,{installType:p}),3===t&&(0,r.createElement)(F,{installType:p})),(0,r.createElement)(H,{currentPage:t,numberOfPages:4,setCurrentPage:a,onClose:y})))}}}]);