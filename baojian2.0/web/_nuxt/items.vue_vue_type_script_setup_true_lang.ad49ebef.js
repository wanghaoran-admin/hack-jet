import{a as x,T as g,o as t,c,j as u,f as a,V as d,h as o,t as l,m as i,W as s,u as n,A as w,i as m,F as b,cx as k,cy as S}from"./entry.3d67c050.js";import{E as B}from"./index.3a9cc1a4.js";const v={key:0,class:"before:w-[6px] mt-4 before:h-[6px] before:bg-primary before:block flex items-center before:rounded-[6px] before:mr-2.5 before:flex-none"},T={class:"line-clamp-1 flex-1 font-medium"},z={key:0,class:"text-tx-secondary ml-4"},A={class:"flex relative"},C={key:0,class:"text-tx-regular line-clamp-2 mt-4"},N={key:1,class:"mt-5 text-tx-secondary flex items-center flex-wrap"},E={key:0},D={key:1,class:"mr-5"},H={key:2,class:"flex items-center"},L=x({__name:"items",props:{index:{type:Number},id:{type:Number},title:{type:String},desc:{type:String},image:{type:String},author:{type:String},click:{type:Number},createTime:{type:String},onlyTitle:{type:Boolean,default:!0},isHorizontal:{type:Boolean,default:!1},titleLine:{type:Number,default:1},border:{type:Boolean,default:!0},source:{type:String,default:"default"},imageSize:{type:String,default:"default"},showAuthor:{type:Boolean,default:!0},showDesc:{type:Boolean,default:!0},showClick:{type:Boolean,default:!0},showTime:{type:Boolean,default:!0},showSort:{type:Boolean,default:!0}},setup(e){const f=e,h=g(()=>{switch(f.imageSize){case"default":return{width:"180px",height:"135px"};case"mini":return{width:"120px",height:"90px"};case"large":return{width:"260px",height:"195px"}}});return(r,V)=>{const y=S;return t(),c(y,{to:`/information/detail/${e.id}`},{default:u(()=>[e.onlyTitle?(t(),a("div",v,[d(r.$slots,"title",{title:e.title},()=>[o("span",T,l(e.title),1)]),e.showTime?(t(),a("span",z,l(e.createTime),1)):i("",!0)])):(t(),a("div",{key:1,class:s({"border-b border-br pb-4":e.border,"flex pt-4 items-center":!e.isHorizontal})},[o("div",A,[e.image?(t(),c(n(B),{key:0,class:s(["flex-none",{"mr-4":!e.isHorizontal}]),src:e.image,fit:"cover",style:w(n(h))},null,8,["class","src","style"])):i("",!0)]),o("div",{class:s(["flex-1",{"p-2":e.isHorizontal}])},[d(r.$slots,"title",{title:e.title},()=>[o("div",{class:s(["text-lg font-medium",`line-clamp-${e.titleLine}`])},l(e.title),3)]),e.showDesc&&e.desc?(t(),a("div",C,l(e.desc),1)):i("",!0),e.showAuthor||e.showTime||e.showClick?(t(),a("div",N,[e.showAuthor&&e.author?(t(),a("span",E,l(e.author)+"\xA0|\xA0 ",1)):i("",!0),e.showTime?(t(),a("span",D,l(e.createTime),1)):i("",!0),e.showClick?(t(),a("div",H,[m(n(b),null,{default:u(()=>[m(n(k))]),_:1}),o("span",null,"\xA0"+l(e.click)+"\u4EBA\u6D4F\u89C8",1)])):i("",!0)])):i("",!0)],2)],2))]),_:3},8,["to"])}}});export{L as _};
