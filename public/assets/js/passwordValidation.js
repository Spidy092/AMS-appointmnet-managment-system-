/* ************************ Password Validation ************************ */
(function(){
    var password = document.querySelector('#password');

    var helperText = {
        charLength: document.querySelector('.helper-text .length'),
        lowercase: document.querySelector('.helper-text .lowercase'),
        uppercase: document.querySelector('.helper-text .uppercase'),
        number: document.querySelector('.helper-text .number'),
        special: document.querySelector('.helper-text .special')
    };

    var pattern = {
        charLength: function() {
            return password.value.length >= 8;
        },
        lowercase: function() {
            var regex = /^(?=.*[a-z]).+$/;
            return regex.test(password.value);
        },
        uppercase: function() {
            var regex = /^(?=.*[A-Z]).+$/;
            return regex.test(password.value);
        },
        number: function() {
            var regex = /^(?=.*[0-9]).+$/;
            return regex.test(password.value);
        },
        special: function() {
            var regex = /^(?=.*[\W_]).+$/;
            return regex.test(password.value);
        }
    };

    password.addEventListener('keyup', function (){
        patternTest(pattern.charLength(), helperText.charLength);
        patternTest(pattern.lowercase(), helperText.lowercase);
        patternTest(pattern.uppercase(), helperText.uppercase);
        patternTest(pattern.number(), helperText.number);
        patternTest(pattern.special(), helperText.special);
        if(
            hasClass(helperText.charLength, 'valid') &&
            hasClass(helperText.lowercase, 'valid') &&
            hasClass(helperText.uppercase, 'valid') &&
            hasClass(helperText.number, 'valid') &&
            hasClass(helperText.special, 'valid')
        ) {
            document.getElementById("errors-list").style.display = "none";
        } else {
            document.getElementById("errors-list").style.display = "block";
        }
    });

    function patternTest(pattern, response) {
        if(pattern) {
            addClass(response, 'valid');
        } else {
            removeClass(response, 'valid');
        }
    }

    function addClass(el, className) {
        if (el.classList) {
            el.classList.add(className);
        } else {
            el.className += ' ' + className;
        }
    }

    function removeClass(el, className) {
        if (el.classList) {
            el.classList.remove(className);
        } else {
            el.className = el.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        }
    }

    function hasClass(el, className) {
        if (el.classList) {
            return el.classList.contains(className);
        } else {
            return new RegExp('(^| )' + className + '( |$)', 'gi').test(el.className);
        }
    }

})();
/* ************************ Password Validation ************************ */
