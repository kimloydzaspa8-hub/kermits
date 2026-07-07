		        async function loadStaffAccounts() {
		            const response = await fetch(STAFF_ACCOUNTS_URL, {
		                headers: { 'Accept': 'application/json' }
		            });

		            if(!response.ok) throw new Error('Unable to load staff accounts.');
		            return response.json();
		        }

			        async function readStaffError(response) {
		            try {
		                const data = await response.json();
		                if(data?.message) return data.message;
		                if(data?.errors) return Object.values(data.errors).flat().join(' ');
		            } catch(error) {}
			            return 'Unable to save staff account.';
			        }

			        const STAFF_PASSWORDS_STORAGE_KEY = 'kermitsStaffRecentPasswords';

			        function loadStaffRecentPasswords() {
			            try {
			                const stored = JSON.parse(localStorage.getItem(STAFF_PASSWORDS_STORAGE_KEY) || '{}');
			                return stored && typeof stored === 'object' ? stored : {};
			            } catch(error) {
			                return {};
			            }
			        }

			        function getStaffRecentPassword(account) {
			            const passwords = loadStaffRecentPasswords();
			            return passwords[String(account.id)] || passwords[String(account.email || '').toLowerCase()] || '';
			        }

			        function saveStaffRecentPassword(account, password) {
			            if(!account || !password) return;
			            const passwords = loadStaffRecentPasswords();
			            passwords[String(account.id)] = password;
			            passwords[String(account.email || '').toLowerCase()] = password;
			            localStorage.setItem(STAFF_PASSWORDS_STORAGE_KEY, JSON.stringify(passwords));
			        }

		        function renderStaffAccounts() {
		            const accounts = (Array.isArray(db.admins) ? db.admins : [])
		                .filter(account => ['Admin', 'Cashier'].includes(account.role));
		            return `
		                <div class="flex items-center justify-between gap-4 mb-4">
		                    <h2 class="text-2xl font-bold" style="color:var(--primary)">Cashier / Staff</h2>
		                    <button type="button" onclick="openStaffModal()" class="btn-pos px-5 py-3"><i class="fas fa-user-plus mr-2"></i>Add Staff</button>
		                </div>
		                <div class="card overflow-x-auto">
		                    <h3 class="font-bold mb-4">Accounts</h3>
		                        <table class="w-full bg-white">
		                            <thead><tr class="bg-gray-100 text-left text-sm">
		                                <th class="p-3">ID</th><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Status</th><th class="p-3">Action</th>
		                            </tr></thead>
		                            <tbody>
		                                ${accounts.map(account => `
		                                    <tr class="border-b">
		                                        <td class="p-3 font-bold text-gray-600">#${escapeHtml(account.id)}</td>
		                                        <td class="p-3 font-bold">${escapeHtml(account.name)}</td>
		                                        <td class="p-3">${escapeHtml(account.email)}</td>
		                                        <td class="p-3">${escapeHtml(account.role)}</td>
		                                        <td class="p-3"><span class="px-2 py-1 rounded-full text-xs font-bold ${account.status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${escapeHtml(account.status || 'Active')}</span></td>
				                                        <td class="p-3">
			                                            <div class="flex items-center gap-4">
			                                                <button type="button" onclick="toggleStaffStatus(${account.id})" class="${account.status === 'Active' ? 'text-red-600 hover:text-red-800' : 'text-green-700 hover:text-green-800'} text-lg" title="${account.status === 'Active' ? 'Disable' : 'Activate'}"><i class="fas ${account.status === 'Active' ? 'fa-ban' : 'fa-check'}"></i></button>
			                                                <button type="button" onclick="openStaffModal(${account.id})" class="text-blue-600 hover:text-blue-800 text-lg" title="Edit"><i class="fas fa-pen"></i></button>
			                                                <button type="button" onclick="deleteStaffAccount(${account.id})" class="text-red-600 hover:text-red-800 text-lg" title="Delete"><i class="fas fa-trash"></i></button>
				                                            </div>
				                                        </td>
		                                    </tr>
		                                `).join('')}
		                                ${accounts.length === 0 ? '<tr><td class="p-3 text-gray-400" colspan="6">No staff accounts yet.</td></tr>' : ''}
		                            </tbody>
		                        </table>
		                </div>
				                <div id="staff-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-3">
					                    <form class="card w-full max-w-sm border" style="border-color:#D8CFCA;padding:0;border-radius:1.25rem;overflow:hidden;max-height:calc(100vh - 24px);display:flex;flex-direction:column;" onsubmit="saveStaffAccount(event)">
					                        <input id="staff-edit-id" type="hidden">
					                        <div class="flex items-center justify-between gap-4 px-5 py-4" style="border-bottom:1px solid #E5DDD8;">
					                            <h3 id="staff-modal-title" class="font-bold text-base">Create Account</h3>
					                            <button type="button" onclick="closeStaffModal()" class="text-gray-500 hover:text-gray-900 text-2xl leading-none">&times;</button>
					                        </div>
						                    <div class="flex-1 overflow-y-auto px-5 py-3">
				                        <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
				                        <input id="staff-name" class="w-full border rounded px-3 py-2 text-sm mb-3" required>
				                        <label class="block text-xs font-bold text-gray-700 mb-1">Email</label>
				                        <input id="staff-email" type="email" class="w-full border rounded px-3 py-2 text-sm mb-3" required>
					                        <label class="block text-xs font-bold text-gray-700 mb-1">Current Password</label>
						                        <div class="relative mb-1">
						                            <input id="staff-password" type="password" class="w-full border rounded px-3 py-2 pr-11 text-sm" required>
						                            <button type="button" onclick="toggleStaffPassword()" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-900" aria-label="Show password">
						                                <i id="staff-password-eye" class="fas fa-eye"></i>
						                            </button>
						                        </div>
						                        <p id="staff-password-help" class="hidden text-xs text-gray-500 mb-2">Leave blank to keep current password.</p>
				                        <label class="block text-xs font-bold text-gray-700 mb-1">New Password</label>
					                        <div class="relative mb-3">
					                            <input id="staff-new-password" type="password" class="w-full border rounded px-3 py-2 pr-11 text-sm" placeholder="Enter new password">
					                            <button type="button" onclick="toggleStaffNewPassword()" class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-900" aria-label="Show new password">
					                                <i id="staff-new-password-eye" class="fas fa-eye"></i>
					                            </button>
					                        </div>
				                        <label class="block text-xs font-bold text-gray-700 mb-1">Role</label>
				                        <select id="staff-role" class="w-full border rounded px-3 py-2 text-sm">
					                            <option>Cashier</option>
					                            <option>Admin</option>
				                        </select>
						                    </div>
					                        <div class="bg-white px-5 py-4" style="border-top:1px solid #E5DDD8;">
							                        <button id="staff-submit-btn" type="submit" class="btn-pos w-full text-sm font-bold" style="padding:12px;"><i class="fas fa-user-plus mr-2"></i>Create</button>
					                        </div>
					                    </form>
			                </div>
		            `;
		        }

			        function resetStaffForm() {
			            document.getElementById('staff-edit-id').value = '';
			            document.getElementById('staff-name').value = '';
				            document.getElementById('staff-email').value = '';
				            document.getElementById('staff-password').value = '';
				            document.getElementById('staff-password').type = 'password';
				            document.getElementById('staff-password-eye').className = 'fas fa-eye';
				            document.getElementById('staff-new-password').value = '';
				            document.getElementById('staff-new-password').type = 'password';
				            document.getElementById('staff-new-password-eye').className = 'fas fa-eye';
				            document.getElementById('staff-role').value = 'Cashier';
			            document.getElementById('staff-password').required = true;
			            document.getElementById('staff-password-help').classList.add('hidden');
			            document.getElementById('staff-modal-title').textContent = 'Create Account';
			            document.getElementById('staff-submit-btn').innerHTML = '<i class="fas fa-user-plus mr-2"></i>Create';
			        }

			        function openStaffModal(accountId = null) {
			            const modal = document.getElementById('staff-modal');
			            if(modal) {
			                resetStaffForm();
			                if(accountId !== null) {
			                    const account = (db.admins || []).find(item => Number(item.id) === Number(accountId));
				                    if(account) {
				                        document.getElementById('staff-edit-id').value = account.id;
				                        document.getElementById('staff-name').value = account.name || '';
				                        document.getElementById('staff-email').value = account.email || '';
				                        document.getElementById('staff-role').value = account.role || 'Cashier';
				                        document.getElementById('staff-password').value = getStaffRecentPassword(account);
				                        document.getElementById('staff-password').required = false;
				                        document.getElementById('staff-password-help').classList.remove('hidden');
			                        document.getElementById('staff-modal-title').textContent = 'Edit Account';
			                        document.getElementById('staff-submit-btn').innerHTML = '<i class="fas fa-save mr-2"></i>Save Changes';
			                    }
			                }
			                modal.classList.remove('hidden');
			                modal.classList.add('flex');
		            }
		        }

			        function closeStaffModal() {
			            const modal = document.getElementById('staff-modal');
			            if(modal) {
			                modal.classList.add('hidden');
			                modal.classList.remove('flex');
			            }
			        }

			        function toggleStaffPassword() {
			            const input = document.getElementById('staff-password');
			            const icon = document.getElementById('staff-password-eye');
			            const showing = input.type === 'text';
			            input.type = showing ? 'password' : 'text';
			            icon.className = showing ? 'fas fa-eye' : 'fas fa-eye-slash';
			        }

			        function toggleStaffNewPassword() {
			            const input = document.getElementById('staff-new-password');
			            const icon = document.getElementById('staff-new-password-eye');
			            const showing = input.type === 'text';
			            input.type = showing ? 'password' : 'text';
			            icon.className = showing ? 'fas fa-eye' : 'fas fa-eye-slash';
			        }

			        async function saveStaffAccount(event) {
			            event.preventDefault();
			            const editId = document.getElementById('staff-edit-id').value;
			            const name = document.getElementById('staff-name').value.trim();
			            const email = document.getElementById('staff-email').value.trim().toLowerCase();
				            const currentPassword = document.getElementById('staff-password').value.trim();
				            const newPassword = document.getElementById('staff-new-password').value.trim();
				            const password = newPassword || currentPassword;
			            const role = document.getElementById('staff-role').value;

			            const response = await fetch(editId ? `${STAFF_ACCOUNTS_URL}/${editId}` : STAFF_ACCOUNTS_URL, {
			                method: editId ? 'PUT' : 'POST',
			                headers: {
			                    'Content-Type': 'application/json',
			                    'Accept': 'application/json',
			                    'X-CSRF-TOKEN': CSRF_TOKEN
			                },
			                body: JSON.stringify({ name, email, password, role })
			            });

				            if(!response.ok) {
				                showAdminAlert('error', 'Account Error', await readStaffError(response));
				                return;
				            }

				            const savedAccount = await response.json();
				            saveStaffRecentPassword(savedAccount, password);
				            db.admins = await loadStaffAccounts();
			            showAdminAlert('success', editId ? 'Account Updated' : 'Account Created', editId ? 'Staff account details were saved.' : `${role} can now log in.`).then(() => navigateTo('staff'));
		        }

		        async function toggleStaffStatus(accountId) {
		            const response = await fetch(`${STAFF_ACCOUNTS_URL}/${accountId}/status`, {
		                method: 'PATCH',
		                headers: {
		                    'Accept': 'application/json',
		                    'X-CSRF-TOKEN': CSRF_TOKEN
		                }
		            });

		            if(!response.ok) {
		                showAdminAlert('error', 'Status Error', await readStaffError(response));
		                return;
		            }

		            db.admins = await loadStaffAccounts();
		            navigateTo('staff');
		        }

		        async function deleteStaffAccount(accountId) {
		            const account = (db.admins || []).find(item => Number(item.id) === Number(accountId));
		            if(!account) return;

		            const activeAdmins = (db.admins || []).filter(item => item.role === 'Admin' && item.status === 'Active');
		            if(account.role === 'Admin' && account.status === 'Active' && activeAdmins.length <= 1) {
		                showAdminAlert('error', 'Cannot Delete', 'Keep at least one active admin account.');
		                return;
		            }

		            const runDelete = async () => {
		                const response = await fetch(`${STAFF_ACCOUNTS_URL}/${accountId}`, {
		                    method: 'DELETE',
		                    headers: {
		                        'Accept': 'application/json',
		                        'X-CSRF-TOKEN': CSRF_TOKEN
		                    }
		                });

		                if(!response.ok) {
		                    showAdminAlert('error', 'Delete Error', await readStaffError(response));
		                    return;
		                }

		                db.admins = await loadStaffAccounts();
		                showAdminAlert('success', 'Account Deleted', `${account.name} was removed.`).then(() => navigateTo('staff'));
		            };

		            if(window.Swal) {
		                const result = await Swal.fire({
		                    icon: 'warning',
		                    title: 'Delete Account?',
		                    text: `Are you sure you want to delete "${account.name}"? This cannot be undone.`,
		                    showCancelButton: true,
		                    customClass: { popup: 'swal-compact' },
		                    confirmButtonColor: '#dc2626',
		                    cancelButtonColor: '#6b7280',
		                    confirmButtonText: 'Delete'
		                });

		                if(result.isConfirmed) runDelete();
		                return;
		            }

		            if(confirm(`Delete "${account.name}"?`)) runDelete();
		        }
