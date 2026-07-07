        let inventorySearchTerm = '';

        function renderInventory() {
            return `
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-2xl font-bold" style="color:var(--primary)">Ingredient Inventory</h2>
                        <p class="text-gray-500">Add ingredients, restock supplies, and record used quantities.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <button type="button" onclick="showRestockInventoryItem()" class="px-3 py-2 text-sm font-bold text-white rounded-lg" style="background:#047857;">
                            <i class="fas fa-box-open mr-1"></i> Restock
                        </button>
                        <button type="button" onclick="showAddInventoryItem()" class="btn-pos px-3 py-2 text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Item
                        </button>
                    </div>
                </div>
                ${db.inventory.filter(i => Number(i.stock) <= Number(i.reorder)).length > 0 ? `<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-5" role="alert"><i class="fas fa-bell mr-3"></i> Low Stock Alert! Please reorder highlighted items.</div>` : ''}
                ${db.inventory.length === 0 ? `
                    <div class="card text-center text-gray-500">
                        <h3 class="font-bold text-lg mb-2" style="color:var(--primary)">No inventory items yet</h3>
                        <p>Add an item above to start tracking inventory.</p>
                    </div>
                ` : `
                    <div class="card overflow-x-auto">
                        <div class="mb-4 flex justify-between items-center gap-3">
                            <h3 class="font-bold">Inventory Items</h3>
                            <div class="relative w-full md:w-80">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input id="inventory-search" type="search" value="${escapeHtml(inventorySearchTerm)}" oninput="inventorySearchTerm = this.value; renderContent();" placeholder="Search inventory" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2">
                            </div>
                        </div>
                        <table class="w-full bg-white text-left" style="color:#0f172a;">
                            <thead>
                                <tr class="bg-gray-100 text-sm">
                                    <th class="p-3 font-semibold">ID</th>
                                    <th class="p-3 font-semibold">Name</th>
                                    <th class="p-3 font-semibold">Unit</th>
                                    <th class="p-3 font-semibold">Status</th>
                                    <th class="p-3 font-semibold">Stock</th>
                                    <th class="p-3 font-semibold">Reorder</th>
                                    <th class="p-3 font-semibold">Use</th>
                                    <th class="p-3 font-semibold">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${db.inventory.map((item, index) => ({ item, index })).filter(({ item, index }) => {
                                    const search = String(inventorySearchTerm || '').trim().toLowerCase();
                                    if(!search) return true;
                                    const lowStock = Number(item.stock) <= Number(item.reorder);
                                    return [
                                        `#${index + 1}`,
                                        String(index + 1),
                                        item.name,
                                        item.unit || 'pcs',
                                        lowStock ? 'Low Stock' : 'Active',
                                        Number(item.stock) || 0,
                                        Number(item.reorder) || 0
                                    ].join(' ').toLowerCase().includes(search);
                                }).map(({ item, index }) => {
                                    const lowStock = Number(item.stock) <= Number(item.reorder);
                                    return `
                                        <tr class="border-b hover:bg-gray-50 text-sm ${lowStock ? 'bg-red-50' : ''}">
                                            <td class="p-3 font-bold text-gray-600">#${index + 1}</td>
                                            <td class="p-3 font-bold text-gray-900">${escapeHtml(item.name)}</td>
                                            <td class="p-3 text-gray-700">${escapeHtml(item.unit || 'pcs')}</td>
                                            <td class="p-3">
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold ${lowStock ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">
                                                    ${lowStock ? 'Low Stock' : 'Active'}
                                                </span>
                                            </td>
                                            <td class="p-3 font-bold ${lowStock ? 'text-red-700' : 'text-gray-900'}">${Number(item.stock) || 0}</td>
                                            <td class="p-3 text-gray-700">${Number(item.reorder) || 0}</td>
                                            <td class="p-3">
                                                <div class="flex items-center gap-2 min-w-32">
                                                    <input type="number" id="use-${index}" min="1" placeholder="Qty" class="w-20 border border-gray-300 rounded-md px-2 py-1.5">
                                                    <button onclick="useInventoryItem(${index})" class="text-sm font-bold text-red-600">Use</button>
                                                </div>
                                            </td>
                                            <td class="p-3">
                                                <div class="flex items-center gap-4">
                                                    <button onclick="showEditInventoryItem(${index})" class="text-blue-600 hover:text-blue-800 text-lg" title="Edit"><i class="fas fa-pen"></i></button>
                                                    <button onclick="removeInventoryItem(${index})" class="text-red-600 hover:text-red-800 text-lg" title="Delete"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                }).join('') || '<tr><td class="p-3 text-gray-500 text-center" colspan="8">No inventory items match your search.</td></tr>'}
                            </tbody>
                        </table>
                    </div>
                `}
            `;
        }

        async function addInventoryItem(name, unit, stock, reorder) {
            if(!name) {
                showAdminAlert('warning', 'Missing Item Name', 'Please enter an inventory item name.');
                return;
            }

            if(db.inventory.some(item => String(item.name).toLowerCase() === name.toLowerCase())) {
                showAdminAlert('warning', 'Item Exists', 'This inventory item already exists. Use Restock instead.');
                return;
            }

            db.inventory.push({ name, unit, stock, reorder, reorderPoint: reorder });
            await saveDB();
            await showAdminAlert('success', 'Item Added', 'Inventory item added successfully.');
            navigateTo('inventory');
        }

        function showAddInventoryItem() {
            if(!window.Swal) {
                const name = prompt('Item name');
                if(!name) return;
                const unit = prompt('Unit', 'g') || 'pcs';
                const stock = Math.max(0, Number(prompt('Starting qty', '0')) || 0);
                const reorder = Math.max(0, Number(prompt('Low stock alert qty', '0')) || 0);
                addInventoryItem(name.trim(), unit.trim(), stock, reorder);
                return;
            }

            Swal.fire({
                title: 'Add Item',
                html: `
                    <div style="display:grid;gap:10px;text-align:left;">
                        <input id="inventory-new-name" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Item name">
                        <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:10px;">
                            <input id="inventory-new-unit" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Unit" value="g">
                            <input id="inventory-new-stock" type="number" min="0" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Starting qty">
                        </div>
                        <input id="inventory-new-reorder" type="number" min="0" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Low stock alert qty">
                    </div>
                `,
                width: 420,
                padding: '1rem',
                customClass: { popup: 'swal-compact' },
                confirmButtonText: 'Add Item',
                confirmButtonColor: '#5D4037',
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    const name = document.getElementById('inventory-new-name').value.trim();
                    const unit = document.getElementById('inventory-new-unit').value.trim() || 'pcs';
                    const stock = Math.max(0, Number(document.getElementById('inventory-new-stock').value) || 0);
                    const reorder = Math.max(0, Number(document.getElementById('inventory-new-reorder').value) || 0);

                    if(!name) {
                        Swal.showValidationMessage('Please enter an item name.');
                        return false;
                    }

                    return { name, unit, stock, reorder };
                }
            }).then(result => {
                if(result.isConfirmed && result.value) {
                    addInventoryItem(result.value.name, result.value.unit, result.value.stock, result.value.reorder);
                }
            });
        }

        function showRestockInventoryItem() {
            if(!Array.isArray(db.inventory) || db.inventory.length === 0) {
                showAdminAlert('warning', 'No Items', 'Add an inventory item first.');
                return;
            }

            if(!window.Swal) {
                const itemName = prompt('Item name to restock');
                if(!itemName) return;
                const index = db.inventory.findIndex(item => String(item.name).toLowerCase() === itemName.trim().toLowerCase());
                const qty = parseInt(prompt('Restock quantity', '1'));
                restockInventoryByIndex(index, qty);
                return;
            }

            const options = db.inventory.map((item, index) => `<option value="${index}">${escapeHtml(item.name)} (${Number(item.stock) || 0} ${escapeHtml(item.unit || 'pcs')})</option>`).join('');

            Swal.fire({
                title: 'Restock Item',
                html: `
                    <div style="display:grid;gap:10px;text-align:left;">
                        <select id="inventory-restock-index" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;">
                            ${options}
                        </select>
                        <input id="inventory-restock-qty" type="number" min="1" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Quantity to add">
                    </div>
                `,
                width: 420,
                padding: '1rem',
                customClass: { popup: 'swal-compact' },
                confirmButtonText: 'Restock',
                confirmButtonColor: '#047857',
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    const index = Number(document.getElementById('inventory-restock-index').value);
                    const qty = parseInt(document.getElementById('inventory-restock-qty').value);

                    if(!Number.isInteger(index) || !db.inventory[index]) {
                        Swal.showValidationMessage('Please choose an item.');
                        return false;
                    }

                    if(!qty || qty <= 0) {
                        Swal.showValidationMessage('Please enter a valid quantity.');
                        return false;
                    }

                    return { index, qty };
                }
            }).then(result => {
                if(result.isConfirmed && result.value) {
                    restockInventoryByIndex(result.value.index, result.value.qty);
                }
            });
        }

        async function restockInventoryByIndex(index, qty) {
            const item = db.inventory[index];
            if(!item || !qty || qty <= 0) return;

            item.stock = (Number(item.stock) || 0) + qty;
            await saveDB();
            await showAdminAlert('success', 'Item Restocked', `${item.name} stock was updated.`);
            navigateTo('inventory');
        }

        function showEditInventoryItem(index) {
            const item = db.inventory[index];
            if(!item) return;

            if(!window.Swal) {
                const name = prompt('Item name', item.name) || item.name;
                const unit = prompt('Unit', item.unit || 'pcs') || item.unit || 'pcs';
                const stock = Math.max(0, Number(prompt('Stock', Number(item.stock) || 0)) || 0);
                const reorder = Math.max(0, Number(prompt('Reorder level', Number(item.reorder) || 0)) || 0);
                updateInventoryItem(index, { name: name.trim(), unit: unit.trim(), stock, reorder });
                return;
            }

            Swal.fire({
                title: 'Edit Item',
                html: `
                    <div style="display:grid;gap:10px;text-align:left;">
                        <input id="inventory-edit-name" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Item name" value="${escapeHtml(item.name)}">
                        <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:10px;">
                            <input id="inventory-edit-unit" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Unit" value="${escapeHtml(item.unit || 'pcs')}">
                            <input id="inventory-edit-stock" type="number" min="0" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Stock" value="${Number(item.stock) || 0}">
                        </div>
                        <input id="inventory-edit-reorder" type="number" min="0" class="swal2-input" style="width:100%;margin:0;box-sizing:border-box;" placeholder="Reorder level" value="${Number(item.reorder) || 0}">
                    </div>
                `,
                width: 420,
                padding: '1rem',
                customClass: { popup: 'swal-compact' },
                confirmButtonText: 'Save Changes',
                confirmButtonColor: '#5D4037',
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    const name = document.getElementById('inventory-edit-name').value.trim();
                    const unit = document.getElementById('inventory-edit-unit').value.trim() || 'pcs';
                    const stock = Math.max(0, Number(document.getElementById('inventory-edit-stock').value) || 0);
                    const reorder = Math.max(0, Number(document.getElementById('inventory-edit-reorder').value) || 0);

                    if(!name) {
                        Swal.showValidationMessage('Please enter an item name.');
                        return false;
                    }

                    const duplicate = db.inventory.some((inventoryItem, itemIndex) =>
                        itemIndex !== index && String(inventoryItem.name).toLowerCase() === name.toLowerCase()
                    );

                    if(duplicate) {
                        Swal.showValidationMessage('This inventory item already exists.');
                        return false;
                    }

                    return { name, unit, stock, reorder };
                }
            }).then(result => {
                if(result.isConfirmed && result.value) {
                    updateInventoryItem(index, result.value);
                }
            });
        }

        async function updateInventoryItem(index, values) {
            const item = db.inventory[index];
            if(!item || !values.name) return;

            item.name = values.name;
            item.unit = values.unit || 'pcs';
            item.stock = Math.max(0, Number(values.stock) || 0);
            item.reorder = Math.max(0, Number(values.reorder) || 0);
            item.reorderPoint = item.reorder;

            await saveDB();
            await showAdminAlert('success', 'Item Updated', 'Inventory item details were saved.');
            navigateTo('inventory');
        }

        async function restockItem(index) {
            const input = document.getElementById(`restock-${index}`);
            const qty = parseInt(input.value);
            if(qty > 0) {
                const item = db.inventory[index];
                if(!item) return;
                item.stock = (Number(item.stock) || 0) + qty;
                await saveDB();
                navigateTo('inventory');
            }
        }

        async function useInventoryItem(index) {
            const input = document.getElementById(`use-${index}`);
            const qty = parseInt(input.value);
            if(qty > 0) {
                const item = db.inventory[index];
                if(!item) return;
                item.stock = Math.max(0, (Number(item.stock) || 0) - qty);
                await saveDB();
                navigateTo('inventory');
            }
        }

        function removeInventoryItem(index) {
            const item = db.inventory[index];
            if(!item) return;

            const runRemove = async () => {
                db.inventory.splice(index, 1);
                await saveDB();
                await showAdminAlert('success', 'Item Removed', 'Inventory item removed successfully.');
                navigateTo('inventory');
            };

            if(window.Swal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Remove Inventory Item',
                    text: `Remove "${item.name}" from inventory?`,
                    width: 320,
                    padding: '1rem',
                    customClass: { popup: 'swal-compact' },
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Remove',
                    cancelButtonText: 'Cancel',
                    showCancelButton: true
                }).then(result => {
                    if(result.isConfirmed) runRemove();
                });
                return;
            }

            if(confirm(`Remove "${item.name}" from inventory?`)) runRemove();
        }
