function initializeFaq(selector = '#questions') {
    const container = document.querySelector(selector);

    if (!container) {
        return;
    }

    const questions = container.querySelectorAll('.ql-editor > h3');

    questions.forEach((question) => {
        if (question.dataset.faqInitialized === 'true') {
            return;
        }

        const answer = question.nextElementSibling;

        if (!answer || answer.tagName !== 'P') {
            return;
        }

        question.dataset.faqInitialized = 'true';

        question.setAttribute('role', 'button');
        question.setAttribute('tabindex', '0');
        question.setAttribute('aria-expanded', 'false');

        const toggleFaq = () => {
            const isOpen = question.classList.toggle('faq-open');

            question.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        };

        question.addEventListener('click', toggleFaq);

        question.addEventListener('keydown', (event) => {
            if (event.key !== 'Enter' && event.key !== ' ') {
                return;
            }

            event.preventDefault();

            toggleFaq();
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initializeFaq();
});

document.addEventListener('livewire:navigated', () => {
    initializeFaq();
});