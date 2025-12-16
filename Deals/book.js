// book.js (updated) — minimal, non-invasive additions to show login modal / redirect when not logged in
(function(){
  // Helper: parse PHP-style price string into number
  function parsePhpAmount(str){ if(!str) return 0; const cleaned=String(str).replace(/[^\d.,]/g,'').replace(/,/g,''); const v=parseFloat(cleaned); return isNaN(v)?0:v; }

  const priceEl = document.querySelector('.price-large')||document.querySelector('.price')||document.querySelector('#priceValue')||document.querySelector('.from-price');
  const hiddenPerPersonAmount = document.getElementById('hiddenPerPersonAmount');
  if(priceEl && hiddenPerPersonAmount){ const per=parsePhpAmount(priceEl.textContent||priceEl.innerText); if(per>0) hiddenPerPersonAmount.value=Math.round(per*100)/100; }

  const bookBtn=document.getElementById('bookNowBtn');
  const confirmModalEl = document.getElementById('confirmModal');
  const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
  const TAX_RATE=0.12;

  function formatPHP(n){ return '₱ '+Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); }

  // --- NEW: login handling ---
  // Assumes you inject `isLoggedIn` (boolean) into the page via PHP:
  // <script>const isLoggedIn = <?php echo $user ? 'true' : 'false'; ?>;</script>
  // If not present, treat as not logged in (safe default).
  const loggedIn = (typeof isLoggedIn !== 'undefined') ? !!isLoggedIn : false;
  const loginModalEl = document.getElementById('loginModal'); // if modal exists on page
  const loginModal = loginModalEl ? new bootstrap.Modal(loginModalEl) : null;
  // Fallback login URL if no modal is present on the current page
  const LOGIN_FALLBACK_URL = '../GoogleAPI/login.php?login=1';
  // If not logged in, tweak button label to hint user
  if (bookBtn && !loggedIn) {
    // preserve original text if you want to revert later; simple UX change:
    try { bookBtn.dataset.origText = bookBtn.innerText; } catch(e){}
    bookBtn.innerText = 'Log in to book';
  }
  // -----------------------

  if(bookBtn){
    bookBtn.addEventListener('click', (ev)=>{
      ev.preventDefault();

      // If not logged in -> show login modal if available, otherwise redirect to login page.
      if (!loggedIn) {
        if (loginModal) {
          loginModal.show();
        } else {
          // fallback redirect to unified login page that can open modal there
          window.location.href = LOGIN_FALLBACK_URL;
        }
        return; // do not proceed to booking logic
      }

      // --- original booking logic (unchanged except for small rounding for tax/total) ---
      const dateVal=(document.getElementById('bookDate')||{}).value||'';
      const guestsVal=parseInt((document.getElementById('guestsSelect')||{}).value||'1',10);
      const perPerson=Number(hiddenPerPersonAmount.value||1764);
      const serviceFee=Number((document.getElementById('hiddenServiceFee')||{}).value||0);
      const subtotal=perPerson*guestsVal;
      // round tax to 2 decimals
      const tax=Math.round((subtotal*TAX_RATE)*100)/100;
      const total=Math.round((subtotal+tax+serviceFee)*100)/100;

      if(document.getElementById('bdPerPerson')) document.getElementById('bdPerPerson').textContent=formatPHP(perPerson.toFixed(2));
      if(document.getElementById('bdGuests')) document.getElementById('bdGuests').textContent=guestsVal;
      if(document.getElementById('bdSubtotal')) document.getElementById('bdSubtotal').textContent=formatPHP(subtotal.toFixed(2));
      if(document.getElementById('bdServiceFee')) document.getElementById('bdServiceFee').textContent=formatPHP(serviceFee.toFixed(2));
      if(document.getElementById('bdTax')) document.getElementById('bdTax').textContent=formatPHP(tax.toFixed(2));
      if(document.getElementById('bdTotal')) document.getElementById('bdTotal').textContent=formatPHP(total.toFixed(2));

      if(document.getElementById('confirmText')) document.getElementById('confirmText').textContent=`You are booking for ${guestsVal} guest(s) on ${dateVal || 'No date selected'}. Proceed to payment?`;

      if(document.getElementById('hiddenBookDate')) document.getElementById('hiddenBookDate').value=dateVal;
      if(document.getElementById('hiddenGuests')) document.getElementById('hiddenGuests').value=guestsVal;
      if(document.getElementById('hiddenPerPersonAmount')) document.getElementById('hiddenPerPersonAmount').value=perPerson;
      if(document.getElementById('hiddenTax')) document.getElementById('hiddenTax').value=tax.toFixed(2);
      if(document.getElementById('hiddenSubtotal')) document.getElementById('hiddenSubtotal').value=subtotal.toFixed(2);
      if(document.getElementById('hiddenTotal')) document.getElementById('hiddenTotal').value=total.toFixed(2);

      if (confirmModal) confirmModal.show();
    });
  }

  const bookingForm=document.getElementById('bookingForm');
  if(bookingForm){
    bookingForm.addEventListener('submit', ()=>{
      // Keep hidden inputs in sync on submit
      if(document.getElementById('hiddenBookDate')) document.getElementById('hiddenBookDate').value=(document.getElementById('bookDate')||{}).value||'';
      if(document.getElementById('hiddenGuests')) document.getElementById('hiddenGuests').value=(document.getElementById('guestsSelect')||{}).value||'1';
    });
  }
})();
