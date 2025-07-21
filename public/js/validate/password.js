function validatePassword(event) {
        const password = document.querySelector("#password");
        const minLength = 6;

        if (password.value.length < minLength) {
            alert("Password must be at least " + minLength + " characters.");
            event.preventDefault(); // prevent form from submitting
            return false;
        }

        return true;
    }

    document.addEventListener("DOMContentLoaded", () => {
        toggleLabel();
        const form = document.querySelector("form");
        form.addEventListener("submit", validatePassword);
    });
