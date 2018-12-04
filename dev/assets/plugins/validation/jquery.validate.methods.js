/*!
 * jQuery Validation Methods
 */

(function($) {
    $.validator.addMethod("emailregex", function(value, element) {
        return this.optional( element ) || /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test( value );
    }, "Please enter a valid email address");

    $.validator.addMethod("phoneUS", function(phone_number, element) {
        phone_number = phone_number.replace(/\s+/g, "");
        return this.optional(element) || phone_number.length > 9 && 
        phone_number.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
    }, "Please enter a valid phone number");

    $.validator.addMethod("zipcode", function(value, element) {
        return this.optional(element) || /^\d{5}(?:-\d{4})?$/.test(value);
    }, "Please provide a valid zipcode.");

    $.validator.addMethod("nospecialchar", function(value, element) {
        return this.optional(element) || /([0-9a-zA-Z\s])$/.test(value);
    }, "It must contain only letters and numbers.");

    $.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[a-z]+$/i.test(value);
    }, "Only letters allowed");
}(jQuery));
