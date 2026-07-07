		        function renderMenuForm(item = null, showCancel = false) {
		            const isEdit = Boolean(item);
		            const isOverlay = isEdit || showCancel;
		            const selectedType = item?.type || '';
		            const isBestSeller = Boolean(item?.bestSeller || item?.isBestSeller || item?.featured);
		            const imagePreview = renderMenuImage(item || {}, 'menu-form-photo flex items-center justify-center text-4xl');
		            return `
<div class="card mb-6 ${isOverlay ? 'mx-auto border' : ''}" ${isOverlay ? 'style="border-color:#D8CFCA; background:#fff; border-radius:1.25rem; overflow:hidden; width:100%; max-width:360px; max-height:calc(100vh - 24px); display:flex; flex-direction:column; padding:0;"' : ''}>
                    <div class="flex items-center justify-between gap-4 px-5 py-4" style="border-bottom:1px solid #E5DDD8;">
                        <h3 class="font-bold text-base" style="color:#1F2937">${isEdit ? 'Edit Menu Item' : 'Add Menu Item'}</h3>
                        ${isOverlay ? '<button type="button" onclick="navigateTo(\'menu\')" class="text-gray-500 hover:text-gray-900 text-2xl leading-none">&times;</button>' : ''}
	                    </div>
			                    <div class="modal-scroll-body flex-1 overflow-y-auto scroll-hidden px-5 py-3">
	                    <input type="hidden" id="menu-id" value="${escapeHtml(item?.id || '')}">
	                    <div>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Category</span>
	                            <div class="relative mb-3">
	                                <input id="menu-type" value="${selectedType ? escapeHtml(menuCategoryLabel(selectedType)) : ''}" placeholder="Type or choose category" class="border rounded px-3 py-2 pr-10 w-full text-sm" onfocus="showMenuCategoryDropdown()" oninput="showMenuCategoryDropdown()">
	                                <button type="button" onclick="toggleMenuCategoryDropdown()" class="absolute inset-y-0 right-0 px-3 text-gray-700" aria-label="Show categories">
	                                    <i class="fas fa-chevron-down text-xs"></i>
	                                </button>
	                                <div id="menu-category-dropdown" class="hidden absolute left-0 right-0 top-full z-50 mt-1 max-h-44 overflow-y-auto rounded border bg-white shadow-lg">
	                                    ${renderMenuCategoryDropdownOptions()}
	                                </div>
	                            </div>
	                        </label>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Name</span>
	                            <input id="menu-name" value="${escapeHtml(item?.name || '')}" placeholder="Name" class="border rounded px-3 py-2 w-full text-sm mb-3">
	                        </label>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Price</span>
	                            <input id="menu-price" type="number" min="0" value="${escapeHtml(item?.price || '')}" placeholder="Price" class="border rounded px-3 py-2 w-full text-sm mb-3">
	                        </label>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Best Seller</span>
	                            <select id="menu-best-seller" class="border rounded px-3 py-2 w-full text-sm mb-3">
	                                <option value="no" ${!isBestSeller ? 'selected' : ''}>No</option>
	                                <option value="yes" ${isBestSeller ? 'selected' : ''}>Yes</option>
	                            </select>
	                        </label>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Description</span>
	                            <textarea id="menu-desc" rows="3" placeholder="Description" class="border rounded px-3 py-2 w-full text-sm mb-3">${escapeHtml(item?.desc || '')}</textarea>
	                        </label>
	                        <label class="block">
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Image URL or emoji</span>
						                        <input id="menu-img" value="${escapeHtml(item?.img || '')}" placeholder="Image URL or emoji" class="border rounded px-3 py-2 w-full text-sm mb-3">
	                        </label>
	                        <div>
	                            <span class="block text-xs font-bold text-gray-700 mb-1">Upload Photo</span>
	                            <div class="flex gap-3 items-start">
						                            <div id="menu-photo-preview">${imagePreview}</div>
						                            <label class="flex-1 block">
							                                <input id="menu-photo-file" type="file" accept="image/jpeg,image/png,image/gif,image/webp,image/bmp" onclick="beginMenuPhotoPick()" onchange="chooseMenuPhoto(this)" class="w-full border rounded px-3 py-2 bg-white text-sm">
						                                <span id="menu-photo-status" class="block text-xs text-gray-500 mt-2">Large photos are compressed before upload.</span>
						                            </label>
						                        </div>
	                        </div>
		                    </div>
	                    </div>
			                    <div class="bg-white px-5 py-4" style="border-top:1px solid #E5DDD8;">
                        <button id="menu-save-btn" type="button" onclick="saveMenuItem()" class="btn-pos w-full text-sm font-bold" style="padding:12px;">${isEdit ? 'Update Item' : 'Add Item'}</button>
	                    </div>
	                </div>
		            `;
	        }

	        function isMenuImage(value) {
	            const src = String(value || '');
	            return src.startsWith('http') || src.startsWith('data:image/') || src.startsWith('/menu-images/');
	        }

	        function renderMenuImage(item, classes) {
	            const img = item.img || '??';
	            if(isMenuImage(img)) {
	                return `<img src="${escapeHtml(img)}" alt="${escapeHtml(item.name || 'Menu item')}" class="${classes}">`;
	            }

	            return `<div class="${classes}">${escapeHtml(img)}</div>`;
	        }

	        function isMenuAvailable(item) {
	            return Number(item?.stock ?? 1) > 0;
	        }

		        function renderAvailabilityBadge(item) {
		            return isMenuAvailable(item)
		                ? '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Available</span>'
		                : '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Unavailable</span>';
		        }

	        const MENU_PHOTO_LIMIT_MB = 40;
	        const MENU_PHOTO_LIMIT_BYTES = MENU_PHOTO_LIMIT_MB * 1024 * 1024;
	        const MENU_PHOTO_UPLOAD_BYTES = 5 * 1024 * 1024;
	        const MENU_PHOTO_MAX_EDGE = 1800;
	        let menuPhotoPreviewUrl = null;
	        let selectedMenuPhotoFile = null;
	        let menuPhotoPickerOpen = false;

	        function beginMenuPhotoPick() {
	            menuPhotoPickerOpen = true;
	        }

	        function resetMenuPhotoSelection() {
	            selectedMenuPhotoFile = null;
	            if(menuPhotoPreviewUrl) {
	                URL.revokeObjectURL(menuPhotoPreviewUrl);
	                menuPhotoPreviewUrl = null;
	            }
	        }

	        function setMenuPhotoStatus(message) {
	            const status = document.getElementById('menu-photo-status');
	            if(status) status.textContent = message || '';
	        }

	        function setMenuSaveBusy(isBusy) {
	            const button = document.getElementById('menu-save-btn');
	            if(!button) return;
	            button.disabled = isBusy;
	            button.classList.toggle('opacity-60', isBusy);
	            button.classList.toggle('cursor-not-allowed', isBusy);
	        }

	        function showMenuPhotoPreview(file) {
	            const preview = document.getElementById('menu-photo-preview');
	            if(!preview) return;

	            if(menuPhotoPreviewUrl) URL.revokeObjectURL(menuPhotoPreviewUrl);
	            menuPhotoPreviewUrl = URL.createObjectURL(file);
	            preview.innerHTML = `<img src="${menuPhotoPreviewUrl}" alt="Menu photo preview" class="menu-form-photo">`;
	        }

	        function loadMenuPhoto(file) {
	            return new Promise((resolve, reject) => {
	                const image = new Image();
	                const url = URL.createObjectURL(file);
	                image.onload = () => {
	                    URL.revokeObjectURL(url);
	                    resolve(image);
	                };
	                image.onerror = () => {
	                    URL.revokeObjectURL(url);
	                    reject(new Error('This photo format cannot be previewed by the browser.'));
	                };
	                image.src = url;
	            });
	        }

	        function menuCanvasBlob(canvas, quality) {
	            return new Promise(resolve => canvas.toBlob(resolve, 'image/jpeg', quality));
	        }

	        async function makeMenuPhotoUpload(file) {
	            const image = await loadMenuPhoto(file);
	            const canvas = document.createElement('canvas');
	            const context = canvas.getContext('2d');
	            const targetRatio = 16 / 9;
	            const sourceRatio = image.naturalWidth / image.naturalHeight;
	            let sourceWidth = image.naturalWidth;
	            let sourceHeight = image.naturalHeight;
	            let sourceX = 0;
	            let sourceY = 0;

	            if(sourceRatio > targetRatio) {
	                sourceWidth = Math.round(image.naturalHeight * targetRatio);
	                sourceX = Math.round((image.naturalWidth - sourceWidth) / 2);
	            } else if(sourceRatio < targetRatio) {
	                sourceHeight = Math.round(image.naturalWidth / targetRatio);
	                sourceY = Math.round((image.naturalHeight - sourceHeight) / 2);
	            }

	            let scale = Math.min(1, MENU_PHOTO_MAX_EDGE / Math.max(sourceWidth, sourceHeight));
	            let quality = 0.9;

	            while(scale > 0.08) {
	                canvas.width = Math.max(1, Math.round(sourceWidth * scale));
	                canvas.height = Math.max(1, Math.round(sourceHeight * scale));
	                context.clearRect(0, 0, canvas.width, canvas.height);
	                context.drawImage(image, sourceX, sourceY, sourceWidth, sourceHeight, 0, 0, canvas.width, canvas.height);

	                const blob = await menuCanvasBlob(canvas, quality);
	                if(blob && blob.size <= MENU_PHOTO_UPLOAD_BYTES) {
	                    const name = `${(file.name || 'menu-photo').replace(/\.[^.]+$/, '')}.jpg`;
	                    return new File([blob], name, { type: 'image/jpeg' });
	                }

	                if(quality > 0.55) {
	                    quality -= 0.1;
	                } else {
	                    scale *= 0.75;
	                    quality = 0.9;
	                }
	            }

	            throw new Error('The photo is too large to prepare.');
	        }

	        async function readMenuPhotoError(response) {
	            try {
	                const data = await response.json();
	                if(data?.message) return data.message;
	            } catch (error) {
	                // Use the status fallback below.
	            }

	            return response.status === 413
	                ? `Photo must be ${MENU_PHOTO_LIMIT_MB} MB or smaller.`
	                : 'Photo upload failed.';
	        }

	        async function uploadPreparedMenuPhoto(file) {
	            const formData = new FormData();
	            formData.append('photo', file);

	            const response = await fetch(MENU_PHOTO_URL, {
	                method: 'POST',
	                headers: {
	                    'Accept': 'application/json',
	                    'X-CSRF-TOKEN': CSRF_TOKEN
	                },
	                body: formData
	            });

	            if(!response.ok) throw new Error(await readMenuPhotoError(response));
	            return response.json();
	        }

	        async function chooseMenuPhoto(input) {
	            menuPhotoPickerOpen = false;
	            const file = input.files?.[0];
	            if(!file) return;

	            // Reset if file is too large
	            if(file.size > MENU_PHOTO_LIMIT_BYTES) {
	                input.value = '';
	                selectedMenuPhotoFile = null;
	                showAdminAlert('warning', 'Photo Too Large', `Please choose a photo up to ${MENU_PHOTO_LIMIT_MB} MB.`);
	                return;
	            }

	            // Validate file type
	            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/x-ms-bmp'];
	            if(!validTypes.includes(file.type)) {
	                input.value = '';
	                selectedMenuPhotoFile = null;
	                showAdminAlert('warning', 'Invalid File Type', 'Please choose a JPG, PNG, GIF, WebP, or BMP image.');
	                return;
	            }

	            try {
	                await loadMenuPhoto(file);
	            } catch (error) {
	                input.value = '';
	                selectedMenuPhotoFile = null;
	                showAdminAlert('warning', 'Cannot Read Image', 'Please choose a JPG, PNG, GIF, WebP, or BMP image that can be previewed by the browser.');
	                return;
	            }

	            selectedMenuPhotoFile = file;
	            showMenuPhotoPreview(file);
	            setMenuPhotoStatus('Photo selected. Click Update Item to upload and save.');
	        }

	        async function uploadSelectedMenuPhoto() {
	            if(!selectedMenuPhotoFile) return null;
	            const preview = document.getElementById('menu-photo-preview');
	            let uploadFile = selectedMenuPhotoFile;

	            setMenuPhotoStatus('Preparing photo...');
	            uploadFile = await makeMenuPhotoUpload(selectedMenuPhotoFile);
	            setMenuPhotoStatus('Photo prepared. Size: ' + (uploadFile.size / 1024).toFixed(0) + ' KB');

	            setMenuPhotoStatus('Uploading photo...');
	            try {
	                const data = await uploadPreparedMenuPhoto(uploadFile);
	                selectedMenuPhotoFile = null;

	                if(preview && data.url) {
	                    if(menuPhotoPreviewUrl) {
	                        URL.revokeObjectURL(menuPhotoPreviewUrl);
	                        menuPhotoPreviewUrl = null;
	                    }
	                    preview.innerHTML = `<img src="${escapeHtml(data.url)}" alt="Menu photo preview" class="menu-form-photo">`;
	                }

	                setMenuPhotoStatus('Photo uploaded successfully!');
	                return data.url || null;
	            } catch (error) {
	                setMenuPhotoStatus('Upload failed: ' + error.message);
	                throw error;
	            }
	        }

						        function renderMenuItemCard(item) {
			            return `
			                <div class="card">
		                    <div class="flex items-start gap-3">
		                        ${isMenuImage(item.img) ? `<img src="${escapeHtml(item.img)}" alt="${escapeHtml(item.name)}" class="menu-inline-photo">` : `<div class="menu-inline-photo flex items-center justify-center text-3xl">${escapeHtml(item.img || '??')}</div>`}
		                        <div class="flex-1">
									<h4 class="font-bold text-lg mb-2" style="color:var(--primary)">${escapeHtml(item.name)}</h4>
		                            ${item.desc ? `<p class="text-sm text-gray-600 mt-1">${escapeHtml(item.desc)}</p>` : ''}
		                            <p class="font-bold text-green-700 mt-1">₱${Number(item.price) || 0}</p>
		                            <div class="mt-2">${renderAvailabilityBadge(item)}</div>
		                        </div>
		                    </div>
		                    <div class="flex gap-2 mt-4">
		                        <button onclick="editMenuItem('${item.id}')" class="px-3 py-2 rounded border text-sm"><i class="fas fa-pen"></i> Edit</button>
		                        <button onclick="deleteMenuItem('${item.id}')" class="px-3 py-2 rounded bg-red-600 text-white text-sm"><i class="fas fa-trash"></i> Delete</button>
		                    </div>
			                </div>
			            `;
			        }

	        function defaultMenuCategoryOrder() {
	            return ['foods', 'pasta', 'drinks', 'cake', 'coffee', 'pastry', 'meal'];
	        }

	        function normalizeMenuCategoryName(name) {
	            return String(name || '')
	                .trim()
	                .toLowerCase()
	                .replace(/&/g, 'and')
	                .replace(/[^a-z0-9]+/g, '-')
	                .replace(/^-+|-+$/g, '');
	        }

	        function getMenuCategories(includeAll = true) {
	            const categoryOrder = defaultMenuCategoryOrder();
	            const itemCategories = Array.isArray(db.menu) ? db.menu.map(item => item.type || 'foods') : [];
	            const categories = [...new Set([...categoryOrder.slice(0, 4), ...itemCategories].filter(Boolean))]
	                .sort((a, b) => {
	                    const aIndex = categoryOrder.indexOf(a);
	                    const bIndex = categoryOrder.indexOf(b);
	                    if(aIndex !== -1 || bIndex !== -1) return (aIndex === -1 ? 999 : aIndex) - (bIndex === -1 ? 999 : bIndex);
	                    return menuCategoryLabel(a).localeCompare(menuCategoryLabel(b));
	                });
	            return includeAll ? ['all', ...categories] : categories;
	        }

	        function renderMenuCategoryDropdownOptions(filter = '') {
	            const search = String(filter || '').trim().toLowerCase();
	            const categories = getMenuCategories(false).filter(category => {
	                return !search || menuCategoryLabel(category).toLowerCase().includes(search);
	            });
	            return categories.map(category => `
	                <button type="button" onclick='chooseMenuCategory(${JSON.stringify(menuCategoryLabel(category))})' class="block w-full px-3 py-2 text-left text-sm hover:bg-gray-100">
	                    ${escapeHtml(menuCategoryLabel(category))}
	                </button>
	            `).join('');
	        }

	        function showMenuCategoryDropdown() {
	            const input = document.getElementById('menu-type');
	            const dropdown = document.getElementById('menu-category-dropdown');
	            if(!input || !dropdown) return;
	            dropdown.innerHTML = renderMenuCategoryDropdownOptions(input.value);
	            dropdown.classList.remove('hidden');
	        }

	        function toggleMenuCategoryDropdown() {
	            const dropdown = document.getElementById('menu-category-dropdown');
	            if(!dropdown) return;
	            if(dropdown.classList.contains('hidden')) {
	                showMenuCategoryDropdown();
	            } else {
	                dropdown.classList.add('hidden');
	            }
	        }

	        function chooseMenuCategory(label) {
	            const input = document.getElementById('menu-type');
	            const dropdown = document.getElementById('menu-category-dropdown');
	            if(input) input.value = label;
	            if(dropdown) dropdown.classList.add('hidden');
	        }

	        function resolveMenuCategoryInput(value) {
	            const rawValue = String(value || '').trim();
	            const normalizedValue = normalizeMenuCategoryName(rawValue);
	            if(!normalizedValue) return '';
	            const existingCategory = getMenuCategories(false).find(category => {
	                return String(category) === rawValue
	                    || normalizeMenuCategoryName(category) === normalizedValue
	                    || normalizeMenuCategoryName(menuCategoryLabel(category)) === normalizedValue;
	            });
	            return existingCategory || normalizedValue;
	        }

	        function menuCategoryLabel(category) {
			            const labels = {
				                all: 'All Category',
		                foods: 'Foods',
	                pasta: 'Pasta',
	                drinks: 'Cafe Drinks',
	                cake: 'Cake',
	                coffee: 'Coffee',
	                pastry: 'Pastry',
	                meal: 'Meals'
	            };
	            return labels[category] || String(category || 'Foods').replace(/[-_]/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
		        }

	        function renderMenuCrud(editId = null) {
            const isAdding = editId === '__new__';
            const editItem = editId ? db.menu.find(item => String(item.id) === String(editId)) : null;
	            const items = [...db.menu].sort((a, b) => String(a.type).localeCompare(String(b.type)) || String(a.name).localeCompare(String(b.name)));
	            const searchTerm = menuSearchTerm.trim().toLowerCase();
		            const visibleItems = searchTerm
		                ? items.filter(item => [item.name, item.type, item.desc, item.price].some(value => String(value || '').toLowerCase().includes(searchTerm)))
		                : items;
			            const categories = getMenuCategories(true);
			            if(!categories.includes(selectedMenuCategory)) selectedMenuCategory = 'all';
			            const categoryItems = selectedMenuCategory === 'all' ? visibleItems : visibleItems.filter(item => (item.type || 'foods') === selectedMenuCategory);
	            return `
				                <div class="flex items-center justify-between gap-4 mb-10">
				                    <h1 class="text-3xl font-bold" style="color:var(--primary); font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight:800;">Menu</h1>
				                    <button onclick="addMenuItem()" class="btn-pos px-5 py-4 text-base leading-none flex items-center gap-2"><i class="fas fa-plus"></i><span>Add Item</span></button>
			                </div>
				                <div class="mb-10 flex flex-wrap justify-center gap-4">
			                        ${categories.map(category => `
			                            <button type="button" onclick='setMenuCategory(${JSON.stringify(String(category))})' class="category-pill ${selectedMenuCategory === category ? 'active' : ''}">
			                                ${escapeHtml(menuCategoryLabel(category))}
			                            </button>
			                        `).join('')}
					                </div>
						                <div class="space-y-6">
					                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
			                                ${categoryItems.map(item => `
				                                        <div class="card menu-admin-card">
			                                            <div class="flex items-start gap-3">
			                                                ${isMenuImage(item.img) ? `<img src="${escapeHtml(item.img)}" alt="${escapeHtml(item.name)}" class="menu-inline-photo">` : `<div class="menu-inline-photo flex items-center justify-center text-3xl">${escapeHtml(item.img || '??')}</div>`}
			                                                <div class="flex-1">
																<h4 class="font-bold text-lg mb-2" style="color:var(--primary)">${escapeHtml(item.name)}</h4>
		                                                    ${item.desc ? `<p class="text-sm text-gray-600 mt-1">${escapeHtml(item.desc)}</p>` : ''}
		                                                    <p class="font-bold text-green-700 mt-1">₱${Number(item.price) || 0}</p>
			                                                    <div class="mt-2">${renderAvailabilityBadge(item)}</div>
	                                                </div>
	                                            </div>
	                                            <div class="flex gap-2 mt-4">
		                                                <button onclick="editMenuItem('${item.id}')" class="px-3 py-1 rounded border text-sm"><i class="fas fa-pen"></i> Edit</button>
		                                                <button onclick="deleteMenuItem('${item.id}')" class="px-3 py-1 rounded bg-red-600 text-white text-sm"><i class="fas fa-trash"></i> Delete</button>
		                                            </div>
		                                        </div>
		                                    `).join('')}
					                </div>
			                    ${categoryItems.length === 0 ? '<p class="text-gray-400">No menu items found in this category.</p>' : ''}
		                </div>
                ${editItem || isAdding ? `
                    <div class="fixed inset-0 z-50 grid place-items-center bg-black bg-opacity-40 p-4">
                        <div class="w-full max-w-sm">
                            ${renderMenuForm(editItem, isAdding)}
                        </div>
                    </div>
                ` : ''}
            `;
        }

	        async function saveMenuItem() {
	            const imageInput = document.getElementById('menu-img');
	            
	            try {
	                setMenuSaveBusy(true);
	                
	                // Handle photo upload if selected
	                if(selectedMenuPhotoFile) {
	                    try {
	                        const photoUrl = await uploadSelectedMenuPhoto();
	                        if(photoUrl && imageInput) {
	                            imageInput.value = photoUrl;
	                        } else {
	                            throw new Error('Photo upload did not return a URL.');
	                        }
	                    } catch (error) {
	                        showAdminAlert('error', 'Upload Failed', error.message || 'Please choose another image.');
	                        return;
	                    }
	                }

	                // Get form values
		                const id = document.getElementById('menu-id').value;
		                const name = document.getElementById('menu-name').value.trim();
		                const price = Number(document.getElementById('menu-price').value);
		                const type = resolveMenuCategoryInput(document.getElementById('menu-type').value);
		                const desc = document.getElementById('menu-desc').value.trim();
		                const bestSeller = document.getElementById('menu-best-seller').value === 'yes';
		                const img = imageInput?.value.trim() || '??';

	                // Validate form data
	                if(!name) {
	                    showAdminAlert('warning', 'Invalid Input', 'Please enter a menu item name.');
	                    return;
	                }
	                
	                if(!Number.isFinite(price) || price < 0) {
	                    showAdminAlert('warning', 'Invalid Price', 'Please enter a valid price greater than or equal to 0.');
	                    return;
	                }

	                if(!type) {
	                    showAdminAlert('warning', 'Invalid Category', 'Please enter or choose a category.');
	                    return;
	                }

	                db.menuCategories = [...new Set([...(Array.isArray(db.menuCategories) ? db.menuCategories : []), type])];
	                saveMenuCategories();

	                // Update or create menu item
	                if(id) {
		                    const item = db.menu.find(menuItem => String(menuItem.id) === String(id));
		                    if(item) {
		                        Object.assign(item, { name, price, stock: Number(item.stock ?? 1), type, img, desc, bestSeller, featured: bestSeller });
		                    } else {
		                        throw new Error('Menu item not found.');
		                    }
		                } else {
	                    db.menu.push({
		                        id: `menu-${Date.now()}`,
		                        name,
		                        price,
		                        stock: 1,
		                        type,
		                        img,
		                        desc,
		                        bestSeller,
		                        featured: bestSeller
		                    });
	                }

	                // Save to database
	                await saveDB();
	                showAdminAlert('success', 'Success', id ? 'Menu item updated successfully.' : 'Menu item added successfully.');
	                navigateTo('menu');
	            } catch (error) {
	                showAdminAlert('error', 'Error', error.message || 'Failed to save menu item.');
	            } finally {
	                setMenuSaveBusy(false);
	            }
	        }

	        function editMenuItem(id) {
	            resetMenuPhotoSelection();
	            currentView = 'menu';
	            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
	            document.getElementById('nav-menu')?.classList.add('active');
	            document.getElementById('main-content').innerHTML = renderMenuCrud(id);
	        }

	        function addMenuItem() {
	            resetMenuPhotoSelection();
	            currentView = 'menu';
	            document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
	            document.getElementById('nav-menu')?.classList.add('active');
	            document.getElementById('main-content').innerHTML = renderMenuCrud('__new__');
        }

	        function setMenuSearch(value) {
	            menuSearchTerm = value;
	            navigateTo('menu');
	            const input = document.getElementById('menu-search');
	            if(input) {
	                input.focus();
	                input.setSelectionRange(input.value.length, input.value.length);
	            }
	        }

        function setMenuCategory(category) {
	            selectedMenuCategory = category;
	            navigateTo('menu');
	        }

	        async function addMenuCategory() {
	            let name = '';
	            if(window.Swal) {
	                const result = await Swal.fire({
	                    title: 'Add Category',
	                    input: 'text',
	                    inputPlaceholder: 'Category name',
	                    width: 340,
	                    padding: '1rem',
	                    customClass: { popup: 'swal-compact' },
	                    confirmButtonColor: '#5D4037',
	                    confirmButtonText: 'Add',
	                    cancelButtonText: 'Cancel',
	                    showCancelButton: true,
	                    inputValidator: value => !String(value || '').trim() ? 'Please enter a category name.' : undefined
	                });
	                if(!result.isConfirmed) return;
	                name = result.value;
	            } else {
	                name = prompt('Category name') || '';
	            }

	            const category = normalizeMenuCategoryName(name);
	            if(!category) {
	                showAdminAlert('warning', 'Invalid Category', 'Please enter a category name.');
	                return;
	            }

	            const categories = getMenuCategories(false);
	            const existingCategory = categories.find(item => item === category || normalizeMenuCategoryName(menuCategoryLabel(item)) === category);
	            if(existingCategory) {
	                selectedMenuCategory = existingCategory;
	                navigateTo('menu');
	                return;
	            }

	            db.menuCategories = [...new Set([...(Array.isArray(db.menuCategories) ? db.menuCategories : []), category])];
	            saveMenuCategories();
	            selectedMenuCategory = category;
	            showAdminAlert('success', 'Category Added', `${menuCategoryLabel(category)} is ready for menu items.`).then(() => navigateTo('menu'));
	        }

		        async function deleteMenuItem(id) {
	            const item = db.menu.find(item => String(item.id) === String(id));
	            if(!item) {
	                showAdminAlert('error', 'Not Found', 'Menu item not found.');
	                return;
	            }
	            
	            Swal.fire({
	                icon: 'warning',
	                title: 'Delete Menu Item',
	                text: `Are you sure you want to delete "${item.name}"? This cannot be undone.`,
	                width: 320,
	                padding: '1rem',
	                customClass: { popup: 'swal-compact' },
	                confirmButtonColor: '#E57373',
	                confirmButtonText: 'Delete',
	                cancelButtonText: 'Cancel',
	                showCancelButton: true
	            }).then(async (result) => {
	                if(result.isConfirmed) {
	                    try {
	                        db.menu = db.menu.filter(item => String(item.id) !== String(id));
	                        await saveDB();
	                        showAdminAlert('success', 'Deleted', 'Menu item deleted successfully.');
	                        navigateTo('menu');
	                    } catch (error) {
	                        showAdminAlert('error', 'Error', error.message || 'Failed to delete menu item.');
	                        navigateTo('menu');
	                    }
	                }
	            });
	        }
