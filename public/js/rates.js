// i separated the JS script so its cleaner 
// an async function to load the currency rates from the api 
async function loadRates() {
  try {
    const response = await fetch('/api/rates', {
      // using a static auth is not the best pratice, but more a proof of concept that we could use a auth with a token to the fetch
      // adding something more real like WTs, OAuth, session cookies, etc
      headers: {
        'Authorization': 'Bearer banana'
      }
    });
    const payload = await response.json();

    const tbody = document.querySelector("#ratesTable tbody");
    // clears html first
    tbody.innerHTML = "";

    if (!payload.success) {
      const row = document.createElement("tr");
      // simple error display
      row.innerHTML = `
        <td colspan="3" style="color: red;">
          Error: ${payload.error?.message || 'Unknown error'}
        </td>
      `;
      tbody.appendChild(row);
      return;
    }
    // for each currency adds a new row on the table
    payload.data.forEach(rate => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td class="currency-td">
          <a href="/item/${rate.currency}" class="currency-link">
            <div class="currency-cell">
              <img src="${rate.icon}" alt="${rate.currency}" width="32">
              <span class="text-table">${rate.currency}</span>
            </div>
          </a>
        </td>

        <td class="numeric">
          <div class="value-cell">
            <span class="text-table">${rate.value}</span>
            <img src="/img/chaosOrb.png" width="16">
          </div>
        </td>

        <td class="numeric">
          <span class="text-table">${rate.listingCount}</span>
        </td>
      `;
      tbody.appendChild(row);
    });

  } catch (error) {
    // catch that display error message
    console.error("Error loading rates:", error);
    const tbody = document.querySelector("#ratesTable tbody");
    tbody.innerHTML = `
      <tr><td colspan="3" style="color: red;">Failed to load rates</td></tr>
    `;
  }
}

function sortTables(index, direction) {
  const tbody = document.querySelector("#ratesTable tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));

  rows.sort((a, b) => {
    let aValue = a.children[index].innerText.trim();
    let bValue = b.children[index].innerText.trim();

    // Try to convert to number if possible
    const aNum = parseFloat(aValue.replace(/,/g, ''));
    const bNum = parseFloat(bValue.replace(/,/g, ''));

    if (!isNaN(aNum) && !isNaN(bNum)) {
      aValue = aNum;
      bValue = bNum;
    }

    if (aValue < bValue) return direction === 'asc' ? -1 : 1;
    if (aValue > bValue) return direction === 'asc' ? 1 : -1;
    return 0;
  });

  // Reattach sorted rows
  rows.forEach(row => tbody.appendChild(row));
}

document.querySelectorAll('th.sorted').forEach((th, index) => {
  th.addEventListener('click', () => {
    let direction = th.classList.contains('sorted-asc') ? 'desc' : 'asc';

    document.querySelectorAll('th.sorted').forEach(h => {
      h.classList.remove('sorted-asc', 'sorted-desc');
    });

    th.classList.add(direction === 'asc' ? 'sorted-asc' : 'sorted-desc');

    sortTables(index, direction);
  });
});

// Filter rows by currency name
function filterTable() {
  const filterValue = document.getElementById('filterInput').value.toLowerCase();
  const rows = document.querySelectorAll("#ratesTable tbody tr");

  rows.forEach(row => {
    const currencyCell = row.querySelector(".currency-td .text-table");
    if (!currencyCell) return;

    const text = currencyCell.innerText.toLowerCase();
    if (text.includes(filterValue)) {
      row.style.display = ""; // show
    } else {
      row.style.display = "none"; // hide
    }
  });
}

// Listen for typing
document.getElementById('filterInput').addEventListener('input', filterTable);

loadRates();
setInterval(loadRates, 300000);
