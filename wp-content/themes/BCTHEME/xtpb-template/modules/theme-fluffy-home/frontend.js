(function(){
 'use strict';
 if(window.fluffyHomeReady)return;window.fluffyHomeReady=true;
 document.addEventListener('click',function(e){
  var filter=e.target.closest('[data-fl-filter]');
  if(filter){
   var scope=filter.closest('.fl-menu'),category=filter.dataset.flFilter;
   scope.querySelectorAll('[data-fl-filter]').forEach(function(b){b.setAttribute('aria-pressed',b===filter?'true':'false');});
   scope.querySelectorAll('[data-fl-category]').forEach(function(card){card.hidden=category!=='all'&&card.dataset.flCategory!==category;});
   scope.querySelector('.fl-menu-grid').classList.toggle('is-filtered',category!=='all');
  }
  var link=e.target.closest('[data-fl-lightbox]');if(!link||typeof HTMLDialogElement==='undefined')return;
  e.preventDefault();var dialog=document.createElement('dialog');dialog.className='fl-lightbox';
  var close=document.createElement('button');close.type='button';close.textContent='Chiudi ×';
  var photo=document.createElement('img');photo.src=link.href;photo.alt=link.querySelector('img')?link.querySelector('img').alt:'';
  var caption=document.createElement('p');caption.textContent=photo.alt;
  dialog.append(close,photo,caption);document.body.append(dialog);dialog.showModal();close.focus();
  close.onclick=function(){dialog.close();};dialog.onclick=function(ev){if(ev.target===dialog)dialog.close();};
  dialog.addEventListener('close',function(){dialog.remove();link.focus();},{once:true});
 });
}());
