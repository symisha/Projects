document.querySelectorAll('.dropdown-toggle').forEach((dropdown) => {
    dropdown.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default link behavior
        const parent = dropdown.parentElement;
        parent.classList.toggle('active'); // Toggle dropdown visibility
    });
});

function handleEmergency(type) {
    switch (type) {
        case 'fire':
            alert('Calling the fire department...');
            // Replace with real call functionality
            break;
        case 'ambulance':
            alert('Calling an ambulance...');
            // Replace with real call functionality
            break;
        case 'police':
            alert('Calling the police...');
            // Replace with real call functionality
            break;
        default:
            console.log('Unknown emergency');
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', (event) => {
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach((dropdown) => {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('active');
        }
    });
});

// script.js

document.addEventListener('DOMContentLoaded', () => {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');

        question.addEventListener('click', () => {
            // Toggle the "active" class on the current FAQ item
            item.classList.toggle('active');

            // Expand or collapse the answer
            if (item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.textContent = '-'; // Change the icon to minus
            } else {
                answer.style.maxHeight = null;
                icon.textContent = '+'; // Change the icon to plus
            }
        });
    });
});