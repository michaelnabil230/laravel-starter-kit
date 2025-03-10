import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';

window.axios = axios;
window._ = _;
window.moment = moment;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
