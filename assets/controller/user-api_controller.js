import { Controller } from 'stimulus';
import axios from 'axios';

export default class extends Controller {
    static values = {
        url: String
    }

    async connect() {
    try {
        const response = await axios.get(this.urlValue);
    } catch (error) {
    }
}
}