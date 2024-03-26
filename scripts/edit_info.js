function encodeForAjax(data) {
	return Object.keys(data).map(function (k) {
		return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
	}).join('&')
}

// async function postProfileData(data) {
// 	console.log(data);
// 	console.log(encodeForAjax(data))
// 	return fetch('../api/api_edit_profile.php', {
// 		method: 'post',
// 		headers: {
// 			'Content-Type': 'application/x-www-form-urlencoded'
// 		},
// 		body: encodeForAjax(data)
// 	})
// }

const buttons = document.querySelectorAll('button.edit');
const password_button = document.querySelector('#password-ready button.edit');

if (buttons.length != 0) {
	buttons.forEach(function (button) {
		button.addEventListener('click', function (event) {
			event.preventDefault();
			const form = button.parentNode;
			// Add the "editing" class to the parent form element
			form.classList.add('editing');
		});
	});
}

if (password_button)
password_button.addEventListener('click', async function (event){
	event.preventDefault();
	const form = password_button.parentNode;
	form.setAttribute('id', 'password-edit');
})

const saves = document.querySelectorAll('button.save');

if (saves.length != 0) {
	saves.forEach(function (save) {
		save.addEventListener('click', async function (event) {
			event.preventDefault();
			const user_id = document.querySelector('.edit-profile').getAttribute('data-id');
			const form = save.parentNode;
			console.log(form);
			// console.log(username);
			console.log(form.querySelector('input').getAttribute('data-field'));
			
			let username = document.querySelector('input[data-field="username"]').value;
			if (username == "" && form.querySelector('input').getAttribute('data-field') === 'username') { form.classList.remove('editing'); console.log("oi"); return; }
			if (username == "") { username = document.querySelector('input[data-field="username"]').placeholder; }

			let name = document.querySelector('input[data-field="name"]').value;
			// if (name == "") { name = document.querySelector('input[data-field="name"]').placeholder; }
			if (name == "" && form.querySelector('input').getAttribute('data-field') === 'name') { form.classList.remove('editing'); return; }
			if (name == "") { name = document.querySelector('input[data-field="name"]').placeholder; }

			let email = document.querySelector('input[data-field="email"]').value;
			// if (email == "") { email = document.querySelector('input[data-field="email"]').placeholder; }
			if (email == "" && form.querySelector('input').getAttribute('data-field') === 'email') { form.classList.remove('editing'); return; }
			if (email == "") { email = document.querySelector('input[data-field="email"]').placeholder; }

			const password =  document.querySelector('input[data-field="password"]').value;
			const confirm_password =  document.querySelector('input[data-field="confirm_password"]').value;
			// console.log(password);
			// console.log(confirm_password);
			
			if ((password == "" || confirm_password=="") && form.querySelector('input').getAttribute('data-field') === 'password') { console.log("entre"); form.setAttribute('id', 'password-ready'); return; }



			// console.log(username);
			// console.log(email);
			// console.log(encodeForAjax({ user_id: user_id, username: username, name: name, email: email }));

			const response = await fetch('../api/api_edit_profile.php', {
				method: 'PUT',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				},
				body: encodeForAjax({ user_id: user_id, username: username, name: name, email: email, password: password, confirm_password: confirm_password })
			})

			if (!response.ok) {
				const message = await response.json();
				drawMessage('error', message.error);

				document.querySelector('input[data-field="username"]').value = '';
				document.querySelector('input[data-field="name"]').value = '';
				document.querySelector('input[data-field="email"]').value = '';
				document.querySelector('input[data-field="password"]').value = '';
				document.querySelector('input[data-field="confirm_password"]').value = '';

				return;
			}

			const message = await response.json();

			drawUpdatedUser(username, name, email);
			drawUpdatedTicket(name, email);
			drawMessage('success', message.success);
			form.classList.remove('editing');
			document.querySelector('input[data-field="confirm_password"]').parentNode.setAttribute('id', 'password-ready');
			// form.setAttribute('id', 'password-ready');
			
			document.querySelector('input[data-field="username"]').value = '';
			document.querySelector('input[data-field="username"]').placeholder = username;

			document.querySelector('input[data-field="email"]').value = '';
			document.querySelector('input[data-field="email"]').placeholder = email;

			document.querySelector('input[data-field="name"]').value = '';
			document.querySelector('input[data-field="name"]').placeholder = name;

			document.querySelector('input[data-field="password"]').value = '';
			document.querySelector('input[data-field="confirm_password"]').value = '';
			
		});
	});
}

// pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"

