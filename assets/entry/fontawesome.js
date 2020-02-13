import {dom, library} from '@fortawesome/fontawesome-svg-core'

import { faSyncAlt} from '@fortawesome/pro-duotone-svg-icons';
import {faAngleRight, faAngleLeft} from '@fortawesome/pro-regular-svg-icons'
//
//
// import {
//     faAngleDoubleLeft,
//     faAngleDoubleRight,
//     faAngleLeft,
//     faAngleRight,
//     faAngleUp,
//     faBars,
//     faTimes
// } from '@fortawesome/pro-regular-svg-icons';
// // import {faStreetView, faParking, faUsers} from '@fortawesome/pro-solid-svg-icons';
// import {faFacebook, faInstagram, faTwitter, faVk} from '@fortawesome/free-brands-svg-icons';
//
//
library.add(
    faSyncAlt,
    faAngleRight, faAngleLeft
);

dom.watch();