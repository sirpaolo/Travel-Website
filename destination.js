// destination.js
// Logs to confirm this JS file has loaded
console.log('destination.js loaded');

// Base URL for backend proxy API
const PROXY_BASE = 'http://localhost:3000';

// Get DOM elements needed for the app
const gallery = document.getElementById('hotel-gallery'); // Where hotel cards are shown
const statusEl = document.getElementById('status');       // Status / message display
const input = document.getElementById('city-input');      // City search input
const button = document.getElementById('search-button');  // Search button

// Ensure gallery element exists before continuing
if (!gallery) {
  console.error('Missing #hotel-gallery');
  throw new Error('#hotel-gallery missing');
}


// Helper URLs

// Builds the hotel search URL using the city query
const searchUrlFor = (q) => `${PROXY_BASE}/search?q=${encodeURIComponent(q)}`;

// Builds the photo URL using a hotel location ID
const photoUrlFor = (locationId) => `${PROXY_BASE}/photos/${locationId}`;


// UI helpers

// Updates the status message text and color
function setStatus(text, isError = false) {
  if (statusEl) {
    statusEl.textContent = text;                 // Set message text
    statusEl.style.color = isError ? '#a00' : '#333'; // Red if error, gray otherwise
  }
}

// Clears all hotel cards from the gallery
function clearGallery() {
  gallery.innerHTML = '';
}

// Escapes HTML characters to prevent injection issues
function escapeHtml(str) {
  if (!str) return '';
  return String(str)
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;')
    .replaceAll("'", '&#039;');
}


// Creates a single hotel card
function makeCard(hotel) {
  // Log which hotel card is being created
  console.log('makeCard called for', hotel && (hotel.name || hotel.location_id));

  // Create main card container
  const card = document.createElement('div');
  card.className = 'hotel-card';

  // Thumbnail 
  const thumb = document.createElement('div');
  thumb.className = 'thumb';

  // Image element with loading placeholder
  const img = document.createElement('img');
  img.src = 'https://via.placeholder.com/600x400?text=Loading+Photo...';
  img.alt = hotel?.name ? `Photo of ${hotel.name}` : 'Hotel photo';
  thumb.appendChild(img);

  // Card body
  const body = document.createElement('div');
  body.className = 'body';

  // Hotel name
  const title = document.createElement('h3');
  title.textContent = hotel?.name || 'Unnamed Hotel';

  // Hotel address
  const info = document.createElement('p');
  info.textContent = hotel?.address_obj?.address_string || hotel?.address || 'Unknown address';

  // Meta row 
  const meta = document.createElement('div');
  meta.className = 'meta';

  // Price display
  const price = document.createElement('div');
  price.className = 'price';
  price.textContent = hotel?.price || hotel?.price_string || '';

  // Book Now button
  const bookBtn = document.createElement('button');
  bookBtn.className = 'btn-book btn-book--force';
  bookBtn.type = 'button';
  bookBtn.textContent = 'Book Now';
  bookBtn.setAttribute('aria-label', `Book ${hotel?.name || 'this hotel'}`);

  // Determine hotel ID used for booking
  const id = hotel?.location_id || hotel?.id || '';

  // Disable booking if no ID exists
  if (!id) {
    bookBtn.disabled = true;
    bookBtn.classList.add('btn-book--disabled');
    bookBtn.title = 'Booking not available';
  }

  // Handle Book Now click
  bookBtn.addEventListener('click', (ev) => {
    ev.stopPropagation(); // Prevent click bubbling
    if (!id) { alert('Booking not available'); return; }
    if (typeof openBookingModal === 'function') {
      openBookingModal(hotel); // Open booking modal
    } 
  });

  // Assemble card layout
  meta.appendChild(price);
  meta.appendChild(bookBtn);

  body.appendChild(title);
  body.appendChild(info);
  body.appendChild(meta);

  card.appendChild(thumb);
  card.appendChild(body);

  // Return card and image reference
  return { card, img };
}

// Fetch hotels and render them
function fetchAndRender(city) {
  // Validate city input
  if (!city || !city.trim()) {
    setStatus('Please enter a city name.', true);
    return;
  }

  clearGallery(); // Remove previous results
  setStatus(`Searching hotels in "${city}"...`);

  // Fetch hotel search results
  fetch(searchUrlFor(city))
    .then(resp => {
      if (!resp.ok) throw new Error('Search HTTP ' + resp.status);
      return resp.json();
    })
    .then(data => {
      const allHotels = data.data || [];

      // Handle empty results
      if (!allHotels.length) {
        setStatus(`No hotels found for "${city}".`, true);
        return;
      }

      // Limit results to 10 hotels
      const hotels = allHotels.slice(0, 10);

      // Update status message
      if (allHotels.length > hotels.length) {
        setStatus(`Showing ${hotels.length} of ${allHotels.length} hotels for "${city}" (limited to 10).`);
      } else {
        setStatus(`Showing ${hotels.length} hotels for "${city}".`);
      }

      // Create and render each hotel card
      hotels.forEach(hotel => {
        if (!hotel || !hotel.location_id) return;

        const { card, img } = makeCard(hotel);
        gallery.appendChild(card);

        // Fetch and assign hotel photo
        fetchPhotoAndSet(hotel.location_id, img);
      });
    })
    .catch(err => {
      console.error('Search error', err);
      setStatus('Error loading hotels: ' + err.message, true);
    });
}


// Fetch photo and update card image
function fetchPhotoAndSet(locationId, imgEl) {
  fetch(photoUrlFor(locationId))
    .then(resp => {
      if (!resp.ok) throw new Error('Photos HTTP ' + resp.status);
      return resp.json();
    })
    .then(data => {
      const photos = data.data || [];

      // Find first photo with available image sizes
      const candidate = photos.find(p => p.images && (p.images.large || p.images.medium || p.images.small));

      // Choose best available image
      const best =
        candidate?.images?.large?.url ||
        candidate?.images?.medium?.url ||
        candidate?.images?.small?.url;

      // Set image source
      imgEl.src = best || 'https://via.placeholder.com/350x200?text=No+Photo';
    })
    .catch(err => {
      console.warn('Photo error for', locationId, err);
      imgEl.src = 'https://via.placeholder.com/350x200?text=Photo+Error';
    });
}


// Events

// Trigger search on button click
button.addEventListener('click', () => {
  fetchAndRender(input.value.trim());
});

// Trigger search on Enter key press
input.addEventListener('keydown', (ev) => {
  if (ev.key === 'Enter') {
    ev.preventDefault();
    button.click();
  }
});


// Initial load

// Load default city hotels on page load
fetchAndRender(input.value || 'Manila');
