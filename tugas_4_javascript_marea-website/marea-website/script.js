// =====================================================
// MAREA - Perfume E-commerce
// Task 1a: Product data array (name, price, description, image)
// Task 1b: Display products on the page using a loop
// Task 2 : Filter products by category
// =====================================================

// ---------- 1a. PRODUCT DATA ARRAY ----------
const products = [
  {
    id: 1,
    name: "Tidal Mist",
    category: "Aquatic",
    intensity: 1, // 1 = Light, 2 = Medium, 3 = Deep
    price: 68,
    description: "Sea salt, bergamot and white musk. Crisp as the first breath on an empty beach.",
    image: "images/tidal-mist.svg",
  },
  {
    id: 2,
    name: "Deep Current",
    category: "Aquatic",
    intensity: 3,
    price: 88,
    description: "Marine accord, black pepper and smoky oud. The pull of open water after dark.",
    image: "images/deep-current.svg",
  },
  {
    id: 3,
    name: "Salt & Citrus",
    category: "Citrus",
    intensity: 1,
    price: 62,
    description: "Yuzu, pink grapefruit and sea salt. A sparkling splash of sunlight.",
    image: "images/salt-citrus.svg",
  },
  {
    id: 4,
    name: "Mandarin Shore",
    category: "Citrus",
    intensity: 2,
    price: 64,
    description: "Mandarin, neroli and salt spray. Golden-hour warmth on the shoreline.",
    image: "images/mandarin-shore.svg",
  },
  {
    id: 5,
    name: "Coral Bloom",
    category: "Floral",
    intensity: 2,
    price: 74,
    description: "Sea peony, coral orchid and driftwood. Soft petals carried on a warm breeze.",
    image: "images/coral-bloom.svg",
  },
  {
    id: 6,
    name: "Lagoon Lily",
    category: "Floral",
    intensity: 1,
    price: 70,
    description: "Water lily, green pear and sea breeze. Calm, clear and quietly radiant.",
    image: "images/lagoon-lily.svg",
  },
  {
    id: 7,
    name: "Driftwood Noir",
    category: "Woody",
    intensity: 3,
    price: 82,
    description: "Cedar, ambergris and smoked vetiver. A beach bonfire as the tide comes in.",
    image: "images/driftwood-noir.svg",
  },
  {
    id: 8,
    name: "Harbor Cedar",
    category: "Woody",
    intensity: 2,
    price: 78,
    description: "Atlas cedar, kelp and tonka bean. Weathered wood and cool harbor air.",
    image: "images/harbor-cedar.svg",
  },
];

const intensityLabels = { 1: "Light", 2: "Medium", 3: "Deep" };

// ---------- DOM ELEMENTS ----------
const productGrid = document.getElementById("productGrid");
const filterButtons = document.getElementById("filterButtons");
const resultCount = document.getElementById("resultCount");
const featuredCard = document.getElementById("featuredCard");
const bagCount = document.getElementById("bagCount");
const toast = document.getElementById("toast");

let bag = 0;
let activeCategory = "All";

// ---------- HELPERS ----------
function formatPrice(price) {
  return "$" + price.toFixed(2);
}

// Small wave icons used for the intensity level (Light / Medium / Deep)
function waveIcons(level) {
  let html = "";
  for (let i = 1; i <= 3; i++) {
    html += `<span class="wave ${i <= level ? "on" : ""}">
      <svg viewBox="0 0 24 12"><path d="M1 8c3-4 5-4 8 0s5 4 8 0 4-3 6-2"/></svg>
    </span>`;
  }
  return html;
}

// ---------- 1b. DISPLAY PRODUCTS WITH A LOOP ----------
function displayProducts(list) {
  productGrid.innerHTML = ""; // clear old cards

  if (list.length === 0) {
    productGrid.innerHTML = `<p class="empty">No scents in this family yet — the tide is still coming in.</p>`;
    resultCount.textContent = "Showing 0 scents";
    return;
  }

  // Loop through every product and create a card
  for (let i = 0; i < list.length; i++) {
    const product = list[i];

    const card = document.createElement("article");
    card.className = "product-card";
    card.style.animationDelay = i * 60 + "ms";
    card.innerHTML = `
      <div class="product-img">
        <img src="${product.image}" alt="${product.name} perfume bottle" />
        <span class="tag">${product.category}</span>
      </div>
      <div class="product-info">
        <div class="level-row">
          <span class="level-label">${intensityLabels[product.intensity]}</span>
          <span class="level-waves">${waveIcons(product.intensity)}</span>
        </div>
        <h3>${product.name}</h3>
        <p class="size">50 ml · Eau de Parfum</p>
        <p class="desc">${product.description}</p>
        <div class="card-bottom">
          <span class="price">${formatPrice(product.price)}</span>
          <button class="add-btn" data-id="${product.id}" title="Add to bag" aria-label="Add ${product.name} to bag">+</button>
        </div>
      </div>
    `;
    productGrid.appendChild(card);
  }

  resultCount.textContent = `Showing ${list.length} ${list.length === 1 ? "scent" : "scents"}`;
}

// ---------- 2. FILTER BY CATEGORY ----------
function createFilterButtons() {
  // Build a unique category list from the product array
  const categories = ["All"];
  products.forEach((product) => {
    if (!categories.includes(product.category)) {
      categories.push(product.category);
    }
  });

  categories.forEach((category) => {
    const btn = document.createElement("button");
    btn.className = "filter-btn" + (category === activeCategory ? " active" : "");
    btn.textContent = category;
    btn.dataset.category = category;
    filterButtons.appendChild(btn);
  });
}

function filterProducts(category) {
  activeCategory = category;

  // Highlight the active button
  document.querySelectorAll(".filter-btn").forEach((btn) => {
    btn.classList.toggle("active", btn.dataset.category === category);
  });

  // Filter the array, then display the result
  const filtered =
    category === "All" ? products : products.filter((product) => product.category === category);

  displayProducts(filtered);
}

filterButtons.addEventListener("click", (event) => {
  if (event.target.classList.contains("filter-btn")) {
    filterProducts(event.target.dataset.category);
  }
});

// ---------- FEATURED HERO CARD ----------
function displayFeatured(product) {
  featuredCard.innerHTML = `
    <div class="featured-top">
      <div class="level-box">
        <span class="level-title">Intensity</span>
        <div class="level-pill">
          <span class="level-name">${intensityLabels[product.intensity]}</span>
          ${waveIcons(product.intensity)}
        </div>
      </div>
      <img src="${product.image}" alt="${product.name} perfume bottle" />
    </div>
    <div class="featured-bottom">
      <h3>${product.name}</h3>
      <p>${product.description}</p>
      <div class="featured-actions">
        <span class="price">${formatPrice(product.price)}</span>
        <div>
          <button class="round-btn light add-btn" data-id="${product.id}" aria-label="Add to bag">+</button>
          <a class="round-btn" href="#collection" aria-label="View details">↗</a>
        </div>
      </div>
    </div>
  `;
}

// ---------- ADD TO BAG ----------
function addToBag(id) {
  const product = products.find((p) => p.id === id);
  bag++;
  bagCount.textContent = bag;
  bagCount.classList.remove("bump");
  void bagCount.offsetWidth; // restart animation
  bagCount.classList.add("bump");
  showToast(`${product.name} added to your bag`);
}

document.addEventListener("click", (event) => {
  const btn = event.target.closest(".add-btn");
  if (btn) addToBag(Number(btn.dataset.id));
});

let toastTimer;
function showToast(message) {
  toast.textContent = message;
  toast.classList.add("show");
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => toast.classList.remove("show"), 2200);
}

// "All Perfume" nav link resets the filter
document.querySelector('[data-nav="All"]').addEventListener("click", () => filterProducts("All"));

// Newsletter
document.getElementById("newsletterForm").addEventListener("submit", (event) => {
  event.preventDefault();
  event.target.reset();
  showToast("Thanks! You're on the list.");
});

// Scent guide wave icons
document.querySelectorAll(".waves-ico").forEach((el) => {
  el.innerHTML = waveIcons(Number(el.dataset.level));
});

// ---------- START ----------
displayFeatured(products[0]);
createFilterButtons();
displayProducts(products);
