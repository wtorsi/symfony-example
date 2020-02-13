'use strict';

class Observer {

    constructor() {
        this.mutationObserver = [];
    }

    disconnect() {
        if (!this.mutationObserver.length) {
            return;
        }

        this.mutationObserver.each((el) => el.disconnect());
        this.mutationObserver = [];
    }

    toArray(obj) {
        const array = [];

        for (let i = (obj || []).length >>> 0; i--;) {
            array[i] = obj[i];
        }

        return array;
    }


    tryFindByTree(observer, parent, selector, callback) {
        parent.querySelectorAll(selector).forEach((node) => callback(node, observer));
    }


    once(selector, callback) {

        let found = false;
        this.observer(selector, (elem, observer) => {
            if (found) {
                return;
            }

            found = true;
            observer ? observer.disconnect() : null;
            callback(elem, observer);
        });
    }

    observer(selector, callback) {

        this.tryFindByTree(null, document, selector, callback);

        const observerCallback = (mutations, observer) => {
            this.toArray(mutations).forEach((mutation) => {

                if (mutation.type === 'childList'
                    && mutation.addedNodes.length > 0
                    && (mutation.addedNodes[0].nodeType === 1 || mutation.addedNodes[0].nodeType === 9)) {


                    if (mutation.addedNodes[0].matches(selector)) {
                        callback(mutation.addedNodes[0]);

                        return;
                    }

                    this.tryFindByTree(observer, mutation.addedNodes[0], selector, callback);
                }
            });
        };


        const mutationObserver = new MutationObserver(observerCallback);
        mutationObserver.observe(document.documentElement, {
            childList: true,
            subtree: true,
            characterData: false
        });

    }
}


const observer = new Observer();
export default observer;
