
// ======================================================
// KRUSHI SEVA KENDRA
// Main JavaScript File
// ======================================================


// ======================================================
// 1. REGISTRATION
// ======================================================

const registerForm = document.getElementById("registerForm");

if (registerForm) {

    registerForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("registerEmail").value.trim();
        const mobile = document.getElementById("mobile").value.trim();
        const password = document.getElementById("registerPassword").value;
        const confirmPassword = document.getElementById("confirmPassword").value;

        const photoInput = document.getElementById("photo");
        const idProofInput = document.getElementById("idProof");

        const photo = photoInput ? photoInput.files[0] : null;
        const idProof = idProofInput ? idProofInput.files[0] : null;


        // ------------------------------
        // Name validation
        // ------------------------------

        if (name === "") {
            alert("Please enter your full name.");
            return;
        }

        const namePattern = /^[A-Za-z ]+$/;

        if (!namePattern.test(name)) {
            alert("Name should contain only letters and spaces.");
            return;
        }


        // ------------------------------
        // Email validation
        // ------------------------------

        if (email === "") {
            alert("Please enter your email.");
            return;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return;
        }


        // ------------------------------
        // Mobile validation
        // ------------------------------

        const mobilePattern = /^[0-9]{10}$/;

        if (!mobilePattern.test(mobile)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }


        // ------------------------------
        // Password validation
        // ------------------------------

        if (password === "") {
            alert("Please enter a password.");
            return;
        }

        if (password.length < 6) {
            alert("Password must contain at least 6 characters.");
            return;
        }


        // ------------------------------
        // Confirm password
        // ------------------------------

        if (confirmPassword === "") {
            alert("Please confirm your password.");
            return;
        }

        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return;
        }


        // ------------------------------
        // Farmer photo
        // ------------------------------

        if (!photo) {
            alert("Please upload your farmer photo.");
            return;
        }

        const maxFileSize = 2 * 1024 * 1024;

        if (photo.size > maxFileSize) {
            alert("Farmer photo must be less than 2 MB.");
            return;
        }

        const allowedPhotoTypes = [
            "image/jpeg",
            "image/png"
        ];

        if (!allowedPhotoTypes.includes(photo.type)) {
            alert("Farmer photo must be JPG or PNG.");
            return;
        }


        // ------------------------------
        // ID proof
        // ------------------------------

        if (!idProof) {
            alert("Please upload your ID proof.");
            return;
        }

        if (idProof.size > maxFileSize) {
            alert("ID proof must be less than 2 MB.");
            return;
        }

        const allowedIdTypes = [
            "image/jpeg",
            "image/png",
            "application/pdf"
        ];

        if (!allowedIdTypes.includes(idProof.type)) {
            alert("ID proof must be JPG, PNG or PDF.");
            return;
        }


        // ------------------------------
        // Create FormData
        // ------------------------------

        const formData = new FormData(registerForm);


        // ------------------------------
        // Send data to PHP
        // ------------------------------

        fetch("php/register.php", {
            method: "POST",
            body: formData
        })

        .then(response => response.text())

        .then(data => {

            console.log("Registration Response:", data);

            const result = data.trim();

            if (result === "Registration successful!") {

                alert("Registration successful! You can now login.");

                registerForm.reset();

                window.location.href = "login.html";

            } else {

                alert(result);

            }

        })

        .catch(error => {

            console.error("Registration Error:", error);

            alert("Unable to register. Please try again.");

        });

    });
}


// ======================================================
// 2. LOGIN
// ======================================================

const loginForm = document.querySelector(".login-form");

if (
    loginForm &&
    !document.getElementById("registerForm") &&
    document.getElementById("email") &&
    document.getElementById("password")
) {

    loginForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const email =
            document.getElementById("email").value.trim();

        const password =
            document.getElementById("password").value;


        // ------------------------------
        // Validation
        // ------------------------------

        if (email === "") {
            alert("Please enter your email or mobile.");
            return;
        }

        if (password === "") {
            alert("Please enter your password.");
            return;
        }


        // ------------------------------
        // Create FormData
        // ------------------------------

        const formData = new FormData();

        formData.append("login", email);
        formData.append("password", password);


        // ------------------------------
        // Send to PHP
        // ------------------------------

        fetch("php/login.php", {
            method: "POST",
            body: formData
        })

        .then(response => response.text())

        .then(data => {

            console.log("Login Response:", data);

            const result = data.trim();

            if (result === "Login successful!") {

                alert("Login successful!");

                window.location.href = "index.html";

            } else {

                alert(result);

            }

        })

        .catch(error => {

            console.error("Login Error:", error);

            alert("Unable to login. Please try again.");

        });

    });
}


// ======================================================
// 3. FARMER SUPPORT
// ======================================================

const supportForm = document.querySelector(
    'form.support-form input[name="name"]'
)?.closest("form");


if (
    supportForm &&
    document.getElementById("question")
) {

    supportForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const name =
            document.getElementById("name").value.trim();

        const mobile =
            document.getElementById("mobile").value.trim();

        const crop =
            document.getElementById("crop").value;

        const category =
            document.getElementById("category").value;

        const question =
            document.getElementById("question").value.trim();


        // ------------------------------
        // Name validation
        // ------------------------------

        if (name === "") {
            alert("Please enter your name.");
            return;
        }

        const namePattern = /^[A-Za-z ]+$/;

        if (!namePattern.test(name)) {
            alert("Name should contain only letters and spaces.");
            return;
        }


        // ------------------------------
        // Mobile validation
        // ------------------------------

        const mobilePattern = /^[0-9]{10}$/;

        if (!mobilePattern.test(mobile)) {
            alert("Please enter a valid 10-digit mobile number.");
            return;
        }


        // ------------------------------
        // Crop
        // ------------------------------

        if (crop === "") {
            alert("Please select a crop.");
            return;
        }


        // ------------------------------
        // Category
        // ------------------------------

        if (category === "") {
            alert("Please select a question category.");
            return;
        }


        // ------------------------------
        // Question
        // ------------------------------

        if (question === "") {
            alert("Please describe your farming question.");
            return;
        }

        if (question.length < 10) {
            alert("Please provide a little more detail about your question.");
            return;
        }


        // ------------------------------
        // Create FormData
        // ------------------------------

        const formData = new FormData();

        formData.append("name", name);
        formData.append("mobile", mobile);
        formData.append("crop", crop);
        formData.append("category", category);
        formData.append("question", question);


        // ------------------------------
        // Send to PHP
        // ------------------------------

        fetch("php/support.php", {
            method: "POST",
            body: formData
        })

        .then(response => response.text())

        .then(data => {

            console.log("Support Response:", data);

            const result = data.trim();

            if (result === "Question submitted successfully!") {

                alert("Your question has been submitted successfully!");

                supportForm.reset();

            } else {

                alert(result);

            }

        })

        .catch(error => {

            console.error("Support Error:", error);

            alert("Unable to submit your question. Please try again.");

        });

    });
}


// ======================================================
// 4. CONTACT FORM
// ======================================================

const contactForm =
    document.querySelector(".contact-form-box form");


if (contactForm) {

    contactForm.addEventListener("submit", function (event) {

        event.preventDefault();

        const name =
            document.getElementById("contact-name").value.trim();

        const email =
            document.getElementById("contact-email").value.trim();

        const message =
            document.getElementById("message").value.trim();


        // ------------------------------
        // Name validation
        // ------------------------------

        if (name === "") {
            alert("Please enter your name.");
            return;
        }

        const namePattern = /^[A-Za-z ]+$/;

        if (!namePattern.test(name)) {
            alert("Name should contain only letters and spaces.");
            return;
        }


        // ------------------------------
        // Email validation
        // ------------------------------

        if (email === "") {
            alert("Please enter your email.");
            return;
        }

        const emailPattern =
            /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(email)) {
            alert("Please enter a valid email address.");
            return;
        }


        // ------------------------------
        // Message validation
        // ------------------------------

        if (message === "") {
            alert("Please enter your message.");
            return;
        }

        if (message.length < 10) {
            alert("Please provide a little more detail in your message.");
            return;
        }


        // ------------------------------
        // Create FormData
        // ------------------------------

        const formData = new FormData();

        formData.append("name", name);
        formData.append("email", email);
        formData.append("message", message);


        // ------------------------------
        // Send to PHP
        // ------------------------------

        fetch("php/contact.php", {
            method: "POST",
            body: formData
        })

        .then(response => response.text())

        .then(data => {

            console.log("Contact Response:", data);

            const result = data.trim();

            if (result === "Message sent successfully!") {

                alert("Your message has been sent successfully!");

                contactForm.reset();

            } else {

                alert(result);

            }

        })

        .catch(error => {

            console.error("Contact Error:", error);

            alert("Unable to send your message. Please try again.");

        });

    });
}

