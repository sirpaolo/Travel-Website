// destinationserver.js
// Log to confirm the proxy server is starting
console.log('proxy.js starting (CORS proxy + debug + caching + limits)');

// Import required modules
const express = require('express');
const https = require('https');

// Create Express app
const app = express();

// Server port
const PORT = 3000;

// TripAdvisor API key
const API_KEY = 'Your API key';


// Simple in-memory cache
const cache = new Map();       // Stores cached responses
const CACHE_TTL = 1000 * 60 * 10; // Cache expiration time (10 minutes)

// Retrieve cached value if still valid
function getCache(key) {
  const entry = cache.get(key);
  if (!entry) return null;
  if (Date.now() > entry.expire) {
    cache.delete(key); // Remove expired entry
    return null;
  }
  return entry.value;
}

// Store value in cache with expiration
function setCache(key, value) {
  cache.set(key, {
    value,
    expire: Date.now() + CACHE_TTL
  });
}


// CORS middleware to allow browser access
app.use((req, res, next) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET,OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
  if (req.method === 'OPTIONS') return res.sendStatus(204);
  next();
});


// Simple statistics for debugging upstream calls
let stats = { upstreamSearchCalls: 0, upstreamPhotosCalls: 0 };

// Log current stats
function logStats() {
  console.log('STATS', JSON.stringify(stats));
}


// Helper function to fetch JSON from upstream API
function fetchJsonWithStatus(url) {
  return new Promise((resolve, reject) => {
    console.log('UPSTREAM GET ->', url);

    https.get(
      url,
      { headers: { 'User-Agent': 'node-proxy/1.0', 'Accept': 'application/json' } },
      (upRes) => {
        const status = upRes.statusCode;
        let body = '';

        // Collect response data
        upRes.on('data', chunk => body += chunk);

        // Handle response end
        upRes.on('end', () => {
          let parsed;
          try {
            parsed = body ? JSON.parse(body) : null;
          } catch {
            parsed = body;
          }

          // Log a short preview of the response
          const preview =
            typeof parsed === 'string'
              ? parsed.slice(0, 800)
              : JSON.stringify(parsed).slice(0, 800);

          console.log('UPSTREAM RESPONSE status=', status, 'preview=', preview);

          resolve({ status, body: parsed });
        });
      }
    ).on('error', err => reject(err));
  });
}

// Send JSON response with CORS headers
function sendJsonWithCorsStatus(res, status, payload) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.status(status).json(payload);
}


// Search endpoint with caching and result limit
app.get('/search', async (req, res) => {
  console.log('INCOMING /search', req.originalUrl);

  const city = req.query.q;
  const limit = Math.max(1, Math.min(100, parseInt(req.query.limit || '10', 10)));

  // Validate query parameter
  if (!city) {
    return sendJsonWithCorsStatus(res, 400, { error: 'missing q' });
  }

  // Build cache key
  const cacheKey = `search_${city.toLowerCase()}_limit${limit}`;
  const cached = getCache(cacheKey);

  // Return cached response if available
  if (cached) {
    console.log('CACHE HIT for /search:', city, 'limit=', limit);
    return sendJsonWithCorsStatus(res, 200, cached);
  }

  console.log('CACHE MISS for /search:', city, 'limit=', limit);

  // Upstream TripAdvisor search URL
  const upstream =
    `https://api.content.tripadvisor.com/api/v1/location/search?searchQuery=${encodeURIComponent(city)}&key=${API_KEY}`;

  try {
    // Track upstream search calls
    stats.upstreamSearchCalls++;
    logStats();

    const { status, body } = await fetchJsonWithStatus(upstream);

    let out = body;

    // Trim search results before caching and returning
    if (status === 200 && body && Array.isArray(body.data)) {
      const originalCount = body.data.length;
      out = Object.assign({}, body, { data: body.data.slice(0, limit) });
      console.log(`Trimming search results: original=${originalCount}, returned=${out.data.length}`);
      setCache(cacheKey, out);
    } else if (status === 200) {
      setCache(cacheKey, body);
    }

    sendJsonWithCorsStatus(res, status, out);
  } catch (err) {
    console.error('/search proxy error', err);
    sendJsonWithCorsStatus(res, 500, {
      error: 'proxy search error',
      details: String(err)
    });
  }
});


// Photos endpoint with caching and photo limit
app.get('/photos/:locationId', async (req, res) => {
  const locationId = req.params.locationId;

  // Limit number of photos returned
  const photosLimit = Math.max(1, Math.min(50, parseInt(req.query.limit || '1', 10)));
  const cacheKey = `photos_${locationId}_limit${photosLimit}`;

  console.log('INCOMING /photos', locationId, 'limit=', photosLimit);

  const cached = getCache(cacheKey);
  if (cached) {
    console.log('CACHE HIT for /photos:', locationId, 'limit=', photosLimit);
    return sendJsonWithCorsStatus(res, 200, cached);
  }

  console.log('CACHE MISS for /photos:', locationId, 'limit=', photosLimit);

  // Upstream TripAdvisor photos URL
  const upstream =
    `https://api.content.tripadvisor.com/api/v1/location/${locationId}/photos?key=${API_KEY}`;

  try {
    // Track upstream photo calls
    stats.upstreamPhotosCalls++;
    logStats();

    const { status, body } = await fetchJsonWithStatus(upstream);

    let out = body;

    // Trim photo results before caching
    if (status === 200 && body && Array.isArray(body.data)) {
      const originalCount = body.data.length;
      out = Object.assign({}, body, { data: body.data.slice(0, photosLimit) });
      console.log(
        `Trimming photos: location=${locationId}, original=${originalCount}, returned=${out.data.length}`
      );
      setCache(cacheKey, out);
    } else if (status === 200) {
      setCache(cacheKey, body);
    }

    sendJsonWithCorsStatus(res, status, out);
  } catch (err) {
    console.error('/photos proxy error', err);
    sendJsonWithCorsStatus(res, 500, {
      error: 'proxy photo error',
      details: String(err)
    });
  }
});


// Start the proxy server
app.listen(PORT, () => {
  console.log(`Proxy listening on http://localhost:${PORT}`);
});
