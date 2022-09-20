<?php

namespace Illuminate\Contracts\Auth {
    interface Guard {
        /**
         * Get the currently authenticated user.
         *
         * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
         */
        public function user();
    }
}
