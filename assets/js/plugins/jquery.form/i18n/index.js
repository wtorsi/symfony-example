'use strict';

import en from './en';

const DEFAULT = 'en';
const FALLBACK = 'en';

class Translator {
    constructor(props) {
        this.dict = {};
    }

    add(...tran) {
        tran.forEach((el) => {
            this.dict[el.locale] = el.translation;
        });
    }

    trans(message, params = {}, locale = DEFAULT) {

        const dict = this.dict[locale];
        if (dict === undefined || dict[message] === undefined) {
            if (locale !== FALLBACK) {
                return this.trans(message, {}, FALLBACK);
            }
            return message;
        }


        message = dict[message];
        for (let key in params) {
            message = message.replace(`{{${key}}}`, params[key])
        }

        return message;
    }
}

const lib = new Translator();
lib.add(en);

export default lib;
