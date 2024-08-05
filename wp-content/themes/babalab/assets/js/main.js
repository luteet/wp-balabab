const body=document.querySelector("body"),html=document.querySelector("html"),main=document.querySelector(".main"),menu=document.querySelectorAll(".header__burger, .header__nav, body"),header=document.querySelector(".header"),isDevMode=html.hasAttribute("data-dev-mode"),imageAspectRatio=document.querySelectorAll(".image-aspect-ratio, figure img");imageAspectRatio.forEach(e=>{e.getAttribute("width")&&e.getAttribute("height")&&e.style.setProperty("--aspect-ratio",`${e.getAttribute("width")}/${e.getAttribute("height")}`)}),body.addEventListener("click",(function(e){function t(t){return e.target.closest(t)}const r=t(".header__lang-target");if(r){r.closest(".header__lang").classList.toggle("is-active")}else t(".header__lang-target")||document.querySelectorAll(".header__lang.is-active").forEach(e=>e.classList.remove("is-active"))}));let mastersSlider,resizeCheck={},windowSize=0;function resizeCheckFunc(e,t,r){windowSize<=e&&(1==resizeCheck[String(e)]||null==resizeCheck[String(e)])&&0!=resizeCheck[String(e)]&&(resizeCheck[String(e)]=!1,r()),windowSize>=e&&(0==resizeCheck[String(e)]||null==resizeCheck[String(e)])&&1!=resizeCheck[String(e)]&&(resizeCheck[String(e)]=!0,t())}function resize(){windowSize=window.innerWidth,header&&html.style.setProperty("--height-header",header.offsetHeight+"px"),html.style.setProperty("--vh",(.01*window.innerHeight).toFixed(2)+"px"),windowSize!=window.innerWidth&&(html.style.setProperty("--svh",(.01*window.innerHeight).toFixed(2)+"px"),main.style.minHeight=main.scrollHeight+"px"),resizeCheckFunc(768,(function(){document.querySelectorAll(".masters__inner").forEach(e=>{const t=e.querySelectorAll(".masters-card");mastersSlider&&mastersSlider.destroy(!0,!0),mastersSlider=new Swiper(e,{loop:!0,spaceBetween:0,effect:"coverflow",grabCursor:!0,centeredSlides:!0,slidesPerView:"auto",coverflowEffect:{rotate:0,stretch:-35,depth:150,modifier:1,slideShadows:!1}});const r=e.querySelector(".masters__current"),s=e.querySelector(".masters__name");s.value=t[mastersSlider.activeIndex].dataset.name,r.value=t[mastersSlider.activeIndex].dataset.id,mastersSlider.on("slideChange",e=>{r.value=t[e.activeIndex].dataset.id,s.value=t[e.activeIndex].dataset.name}),e.parentElement.querySelectorAll(".masters__arrow").forEach(e=>{e.onclick=()=>{e.classList.contains("is-prev")?mastersSlider.slidePrev():e.classList.contains("is-next")&&mastersSlider.slideNext()}})})}),(function(){document.querySelectorAll(".masters__inner").forEach(e=>{const t=e.querySelectorAll(".masters-card");mastersSlider&&mastersSlider.destroy(!0,!0),mastersSlider=new Swiper(e,{loop:!0,slidesPerView:2,spaceBetween:16,centeredSlides:!0});const r=e.querySelector(".masters__current"),s=e.querySelector(".masters__name");s.value=t[mastersSlider.activeIndex].dataset.name,r.value=t[mastersSlider.activeIndex].dataset.id,mastersSlider.on("slideChange",e=>{r.value=t[e.activeIndex].dataset.id,s.value=t[e.activeIndex].dataset.name}),e.parentElement.querySelectorAll(".masters__arrow").forEach(e=>{e.onclick=()=>{e.classList.contains("is-prev")?mastersSlider.slidePrev():e.classList.contains("is-next")&&mastersSlider.slideNext()}})})}))}main.style.minHeight=main.scrollHeight+"px",resize(),window.onresize=resize,document.querySelectorAll(".rating__form-textarea").forEach(e=>{e.addEventListener("input",()=>{e.parentElement.dataset.value=e.value})});const rateBlocks=document.querySelectorAll(".rating__block"),thanksExtend=document.querySelector("#thanks-extend"),thanksStandard=document.querySelector("#thanks-standard");function formProcess(e,t){setTimeout(()=>{rateBlocks.forEach(e=>{e.classList.remove("is-visible")}),e.get("rating"),setTimeout(()=>{rateBlocks.forEach(e=>{e.classList.remove("is-active")}),5==e.get("rating")?thanksExtend.classList.add("is-active"):thanksStandard.classList.add("is-active"),setTimeout(()=>{5==e.get("rating")?thanksExtend.classList.add("is-visible"):thanksStandard.classList.add("is-visible"),t.classList.remove("is-loading")},50)},400)},1e3)}rateBlocks.forEach(e=>{"FORM"==e.nodeName&&e.addEventListener("submit",e=>{e.preventDefault()})});