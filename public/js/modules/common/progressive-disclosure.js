/**
 * 🔍 PROGRESSIVE DISCLOSURE
 * Patrón Apple Store: Revelar información según relevancia y scroll
 */

export class ProgressiveDisclosure {
    constructor() {
        this.observer = null;
        this.init();
    }

    init() {
        this.setupIntersectionObserver();
        this.observeElements();
        this.setupTechSpecs();
    }

    setupIntersectionObserver() {
        const options = {
            root: null,
            rootMargin: '0px 0px -100px 0px',
            threshold: 0.1
        };

        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    // Una vez visible, no necesitamos seguir observando
                    this.observer.unobserve(entry.target);
                }
            });
        }, options);
    }

    observeElements() {
        const sections = document.querySelectorAll('.info-section');
        sections.forEach(section => {
            this.observer.observe(section);
        });
    }

    setupTechSpecs() {
        const specHeaders = document.querySelectorAll('.tech-spec-header');
        specHeaders.forEach(header => {
            header.addEventListener('click', (e) => {
                const item = e.target.closest('.tech-spec-item');
                const isActive = item.classList.contains('active');
                
                // Cerrar otros items
                document.querySelectorAll('.tech-spec-item.active').forEach(activeItem => {
                    if (activeItem !== item) {
                        activeItem.classList.remove('active');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('active', !isActive);
            });
        });
    }

    // Método para revelar secciones programáticamente
    revealSection(selector) {
        const section = document.querySelector(selector);
        if (section) {
            section.classList.add('visible');
        }
    }
}