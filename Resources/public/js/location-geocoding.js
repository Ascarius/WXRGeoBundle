(function ($) {

    // Requires Google Maps API
    if (!window.google || !google.maps) {
        return;
    }

    ({

        $street:    null,
        $city:      null,
        $latitude:  null,
        $longitude: null,
        geocoder:   null,

        bind: function () {
            var self = this;
            $(function () { self.init(); });
        },

        init: function () {
            var self = this;

            this.$street    = $('.wxr-geo-address-street');
            this.$city      = $('.wxr-geo-address-city');
            this.$latitude  = $('.wxr-geo-address-latitude');
            this.$longitude = $('.wxr-geo-address-longitude');
            this.geocoder   = new google.maps.Geocoder();

            this.$street.change(function () {
                self.updateLatLng();
            });

            this.$city.change(function () {
                self.updateLatLng();
            });
        },

        updateLatLng: function () {
            var self = this;

            if (this.$street.val() && this.$city.val()) {

                this.geocoder.geocode(
                    { address: this.$street.val() + ' ' + this.$city.find('option:selected').text() },
                    function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            self.$latitude.val(results[0].geometry.address.lat());
                            self.$longitude.val(results[0].geometry.address.lng());
                        }
                    }
                );

            }

        }

    }).bind();

})(jQuery);
