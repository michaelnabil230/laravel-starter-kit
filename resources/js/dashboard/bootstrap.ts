import axios from 'axios';
import _ from 'lodash';
import moment from 'moment';
import noUiSlider from 'nouislider';

window.axios = axios;
window._ = _;
window.moment = moment;
window.noUiSlider = noUiSlider;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
