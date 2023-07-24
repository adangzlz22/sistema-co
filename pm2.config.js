
module.exports = {
    apps: [
        {
            name: 'laravel-echo-server',
            script: 'laravel-echo-server',
            instances : 1,  // default 'laravel-echo-server' required 1 instance only to work
            exec_mode : 'fork',  // default
            interpreter: 'node', // default
            args: 'start',
            error_file: './storage/logs/pm2/pm2.error.log',
            out_file: './storage/logs/pm2/pm2.out.log',
            pid_file: './storage/logs/pm2/pm2.pid.log',
        },
        {
            name: 'laravel-queue-worker',
            script: 'artisan',
            exec_mode: 'fork', // default
            interpreter: 'php',
            instances: 1,
            args: 'queue:work --tries=5 --sleep=1',
            error_file: './storage/logs/pm2/pm2.error.log',
            out_file: './storage/logs/pm2/pm2.out.log',
            pid_file: './storage/logs/pm2/pm2.pid.log',
        },
    ]
};
