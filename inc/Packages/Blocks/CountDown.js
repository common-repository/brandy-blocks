(function ($) {
    $(document).ready(function () {
        const formatNumber = (num) => num < 10 ? `0${num}` : `${num}`;

        $('.brandy-countdown-wrapper[data-countdown]').each(function () {
            const $this = $(this);
            const finalDate = new Date($this.data('countdown')).getTime();

            // Declare interval here
            let interval;

            function brandyUpdateCountDown() {
                const now = new Date().getTime();
                const difference = finalDate - now;

                // Calculate days, hours, minutes, seconds
                const time = {
                    days: Math.floor(difference / (1000 * 60 * 60 * 24)),
                    hours: Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                    minutes: Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60)),
                    seconds: Math.floor((difference % (1000 * 60)) / 1000)
                };

                if (difference < 0) {
                    Object.keys(time).forEach(key => time[key] = 0);
                    clearInterval(interval);
                }

                $this.find('.brandy-countdown-days').text(formatNumber(time.days));
                $this.find('.brandy-countdown-hours').text(formatNumber(time.hours));
                $this.find('.brandy-countdown-minutes').text(formatNumber(time.minutes));
                $this.find('.brandy-countdown-seconds').text(formatNumber(time.seconds));
            }

            // Initial call and setInterval
            brandyUpdateCountDown();
            interval = setInterval(brandyUpdateCountDown, 1000);
        });
    });
})(window.jQuery);
