// $(document).on("keypress", ".avoidSpecialChars", function (event) {
//     var charCode = event.which;
//     var charStr = String.fromCharCode(charCode);
//     var allowedChars = "()-&.,?!–";  // Include en dash (–)
//     var regex = /^[\p{L}\p{N} ]$/u;
  
//     if (allowedChars.indexOf(charStr) !== -1) {
//       var inputField = $(this);
//       var currentValue = inputField.val();
//       var caretPos = inputField.prop("selectionStart");
//       var beforeCaret = currentValue.substring(0, caretPos).trimEnd();
//       var afterCaret = currentValue.substring(caretPos).trimStart();
  
//       if (!isValidSpecialChar(beforeCaret, afterCaret, charStr, inputField)) {
//         event.preventDefault();
//         showError(inputField, "Special characters must be used correctly.");
//       } else {
//         clearError(inputField);
//       }
//     } else if (!regex.test(charStr)) {
//       event.preventDefault();
//       showError($(this), "This special character is not allowed.");
//     } else {
//       clearError($(this));
//     }
//   });
  
//   $(document).on("paste", ".avoidSpecialChars", function (event) {
//     let inputField = $(this);
//     let originalValue = inputField.val();
  
//     setTimeout(function () {
//       let pastedValue = inputField.val().trim();
//       if (!isValidValue(pastedValue)) {
//         inputField.val(originalValue);
//         showError(
//           inputField,
//           "Special characters are not allowed or not placed correctly."
//         );
//       } else {
//         clearError(inputField);
//       }
//     }, 100); // Delay to ensure proper validation after paste
//   });
  
//   $(document).on("focusout", ".avoidSpecialChars", function () {
//     let inputValue = $(this).val().trim();
//     if (inputValue === "") {
//       clearError($(this));
//     } else if (isValidValue(inputValue)) {
//       clearError($(this));
//     } else {
//       showError($(this), "Special characters are not placed correctly.");
//     }
//   });
  
//   function isValidSpecialChar(before, after, char, inputField) {
//     if (char === "(") {
//       // Allow '(' if there's text before it and no unclosed '(' before it
//       return before.match(/[a-zA-Z0-9 ]$/) && !before.match(/\([^)]*$/);
//     } else if (char === ")") {
//       // Allow ')' if there's a matching '(' before it and text inside
//       return (
//         before.includes("(") && before.match(/\([^()]*$/) && !before.match(/\($/)
//       );
//     } else if (char === "-") {
//       // Allow '-' if surrounded by text, remove it if text is not added after it
//       if (before.length > 0 && before.match(/[a-zA-Z0-9]$/)) {
//         setTimeout(() => {
//           let newVal = inputField.val();
//           let newPos = inputField.prop("selectionStart");
//           if (
//             newVal.charAt(newPos - 1) === "-" &&
//             (newVal.length === newPos ||
//               !newVal.charAt(newPos).match(/[a-zA-Z0-9]/))
//           ) {
//             inputField.val(
//               newVal.substring(0, newPos - 1) + newVal.substring(newPos)
//             );
//             showError(inputField, "Hyphen must be surrounded by text.");
//           }
//         }, 1000);
//         return true;
//       }
//       return false;
//     } else if (char === "&") {
//       // Allow '&' if surrounded by text, remove it if text is not added after it
//       if (before.length > 0 && before.match(/[a-zA-Z0-9]$/)) {
//         setTimeout(() => {
//           let newVal = inputField.val();
//           let newPos = inputField.prop("selectionStart");
//           if (
//             newVal.charAt(newPos - 1) === "&" &&
//             (newVal.length === newPos ||
//               !newVal.charAt(newPos).match(/[a-zA-Z0-9]/))
//           ) {
//             inputField.val(
//               newVal.substring(0, newPos - 1) + newVal.substring(newPos)
//             );
//             showError(inputField, "Ampersand must be surrounded by text.");
//           }
//         }, 1000);
//         return true;
//       }
//       return false;
//     } else if (char === "." || char === "?" || char === "!") {
//       // Allow '.' and '?' if there's text before it
//       return before.length > 0 && before.match(/[a-zA-Z0-9]$/);
//     } else if (char === ",") {
//       // Allow ',' if there's text before it
//       if (before.length > 0 && before.match(/[a-zA-Z0-9]$/)) {
//         setTimeout(() => {
//           let newVal = inputField.val();
//           let newPos = inputField.prop("selectionStart");
//           if (
//             newVal.charAt(newPos - 1) === "," &&
//             (newVal.length === newPos ||
//               !newVal.charAt(newPos).match(/[a-zA-Z0-9]/))
//           ) {
//             inputField.val(
//               newVal.substring(0, newPos - 1) + newVal.substring(newPos)
//             );
//             showError(inputField, "Comma must have text after it.");
//           }
//         }, 1000);
//         return true;
//       }
//       return false;
//     } else if (char === "–") {
//       // Allow en dash if surrounded by text
//       if (before.length > 0 && before.match(/[a-zA-Z0-9]$/)) {
//         setTimeout(() => {
//           let newVal = inputField.val();
//           let newPos = inputField.prop("selectionStart");
//           if (
//             newVal.charAt(newPos - 1) === "–" &&
//             (newVal.length === newPos ||
//               !newVal.charAt(newPos).match(/[a-zA-Z0-9]/))
//           ) {
//             inputField.val(
//               newVal.substring(0, newPos - 1) + newVal.substring(newPos)
//             );
//             showError(inputField, "En dash must be surrounded by text.");
//           }
//         }, 1000);
//         return true;
//       }
//       return false;
//     }
//     return true;
//   }
  
//   function isValidValue(value) {
//     var regex = /^[\p{L}\p{N}()\-&,?.!– ]+$/u;  // Include en dash (–)
//     return regex.test(value) && checkSpecialCharPlacement(value);
//   }
  
//   function checkSpecialCharPlacement(value) {
//     // Check if parentheses are balanced
//     if (!isBalancedParentheses(value)) {
//       return false;
//     }
  
//     // Check invalid patterns for other special characters
//     const invalidPatterns = [
//       /[^a-zA-Z0-9\s]\(/,  // Invalid: special character before (
//       /\)[^a-zA-Z0-9\s]/,  // Invalid: ) followed by special character
//       /[^a-zA-Z0-9\s]\)/,  // Invalid: special character before )
//       /\([^a-zA-Z0-9\s]/,  // Invalid: ( followed by special character
//       /[^a-zA-Z0-9\s]-[^a-zA-Z0-9\s]/,  // Invalid: special character before and after -
//       /[^a-zA-Z0-9\s]&[^a-zA-Z0-9\s]/,  // Invalid: special character before and after &
//       /[^a-zA-Z0-9\s],[^a-zA-Z0-9\s]/,  // Invalid: special character before and after ,
//       /[^a-zA-Z0-9\s]\.[^a-zA-Z0-9\s]/, // Invalid: special character before and after .
//       /[^a-zA-Z0-9\s]\?[^a-zA-Z0-9\s]/, // Invalid: special character before and after ?
//       /[^a-zA-Z0-9\s]!([^a-zA-Z0-9\s]|$)/, // Invalid: special character before and after !
//       /[^a-zA-Z0-9\s]–[^a-zA-Z0-9\s]/,  // Invalid: special character before and after en dash
//     ];
  
//     for (const pattern of invalidPatterns) {
//       if (pattern.test(value)) {
//         return false;
//       }
//     }
  
//     return true;
//   }
  
//   function isBalancedParentheses(text) {
//     let stack = [];
//     for (let char of text) {
//       if (char === "(") {
//         stack.push(char);
//       } else if (char === ")") {
//         if (stack.length === 0) return false;
//         stack.pop();
//       }
//     }
//     return stack.length === 0;
//   }
  
//   function showError(element, message) {
//     let errorSpan = element.next(".error");
//     if (errorSpan.length === 0) {
//       element.after('<span class="error text-danger">' + message + "</span>");
//     } else {
//       errorSpan.text(message);
//     }
//   }
  
//   function clearError(element) {
//     let errorSpan = element.next(".error");
//     if (errorSpan.length > 0) {
//       errorSpan.text("");
//     }
//   }
  
