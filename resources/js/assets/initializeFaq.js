function initializeFaq() {
    const container = document.querySelector('#questions');

    if (!container) {
        return;
    }

    const questions = container.querySelectorAll(':scope > h3');

    questions.forEach((question, index) => {
        const answer = question.nextElementSibling;

        if (!answer || answer.tagName !== 'P') {
            return;
        }

        if (question.dataset.faqInitialized === 'true') {
            return;
        }

        question.dataset.faqInitialized = 'true';

        const questionId = `questions-question-${index}`;
        const answerId = `questions-answer-${index}`;

        question.id = questionId;
        answer.id = answerId;

        question.setAttribute('role', 'button');
        question.setAttribute('tabindex', '0');
        question.setAttribute('aria-expanded', 'false');
        question.setAttribute('aria-controls', answerId);

        answer.setAttribute('role', 'region');
        answer.setAttribute('aria-labelledby', questionId);

        const toggleQuestion = () => {
            const wasOpen = question.classList.contains('faq-open');

            questions.forEach((otherQuestion) => {
                otherQuestion.classList.remove('faq-open');
                otherQuestion.setAttribute('aria-expanded', 'false');
            });

            if (!wasOpen) {
                question.classList.add('faq-open');
                question.setAttribute('aria-expanded', 'true');
            }
        };

        question.addEventListener('click', toggleQuestion);

        question.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleQuestion();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initializeFaq);
document.addEventListener('livewire:navigated', initializeFaq);