@extends('layouts.app_bs5')

@section('title','Dashboard')

@section('content')

<div class="container-fluid py-2">

    <!-- HERO -->
    <section class="hero">
        <div class="row align-items-top">
            <div class="col-lg-12">
              <h3 class="display-6 mb-2 d-flex justify-content-between align-items-center">
                  <div>
                      Welcome back,
                      <span class="fw-bold text-black-90" style="font-family: 'Playfair Display', serif;">
                          {{ Auth::user()->name }}
                      </span>
                      👋
                  </div>
                  <!-- Right Side: The Clock -->
                  <div class="h3 display-5 m-0">
                      <i class="bi bi-clock me-1 text-info"></i>
                      <span id="widget-digital-clock" class="font-sans text-dark fw-medium">
                          --:--:--
                      </span>
                  </div>
              </h3>


                {{-- @unless(Auth::id() == 4)
                <x-attendance-console :attendance="$attendance" :status="$status" />
                @endunless --}}

                @unless(in_array(Auth::id(), [4, 12]))
                    <x-attendance-console :attendance="$attendance" :status="$status" />
                @endunless


                <x-attendance-status-board />

            </div>
        </div>
    </section>

    <!-- CONTENT -->
</div>
<script>

                /*
                |--------------------------------------------------------------------------
                | Header Digital Clock
                |--------------------------------------------------------------------------
                */
                (function(){
                    function updateClock(){
                        const now =
                            new Date();
                        let hours =
                            now.getHours();

                        const minutes =
                            String(now.getMinutes())
                            .padStart(2,'0');

                        const seconds =
                            String(now.getSeconds())
                            .padStart(2,'0');

                        const ampm =
                            hours >= 12 ? 'PM' : 'AM';

                        hours =
                            hours % 12;
                        hours =
                            hours ? hours : 12;
                        hours =
                            String(hours)
                            .padStart(2,'0');

                        const clock =
                            document.getElementById(
                                'widget-digital-clock'
                            );

                        if(clock)
                        {
                            clock.innerText =
                            `${hours}:${minutes}:${seconds} ${ampm}`;
                        }
                    }
                    updateClock();
                    setInterval(updateClock,1000);
                })();
</script>
@endsection
