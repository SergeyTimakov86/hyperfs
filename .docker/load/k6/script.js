// docker-compose run --rm k6 run --summary-mode=full /scripts/script.js

import http from 'k6/http';

export const options = {
    discardResponseBodies: true,
    scenarios: {
        contacts: {
            executor: 'constant-arrival-rate',

            // How long the test lasts
            duration: '600s', // 823/39 //832/45 //830/45 //828/42 //824/41

            // How many iterations per timeUnit
            rate: 100,

            // Start `rate` iterations per second
            timeUnit: '1s',

            // Pre-allocate 2 VUs before starting the test
            preAllocatedVUs: 10,

            // Spin up a maximum of 50 VUs to sustain the defined
            // constant arrival rate.
            maxVUs: 100,
        },
    },
};

export default function () {
    http.get('http://envoy:8080/test');
}
