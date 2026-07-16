<div class="position-fixed bottom-0 end-0 p-4"
     style="z-index: 1080">


    @if(session()->has('success'))

        <div id="dashboard-toast"
             class="toast border-0 shadow-lg rounded-4 overflow-hidden"
             role="alert"
             aria-live="assertive"
             aria-atomic="true">


            <div class="toast-header bg-success-subtle border-0 px-3 py-2">


                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>


                <strong class="me-auto text-success">

                    Success

                </strong>


                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="toast"
                        aria-label="Close">

                </button>


            </div>


            <div class="toast-body bg-white text-dark px-3 py-3">


                {{ session('success') }}


            </div>


        </div>


    @elseif(session()->has('error'))


        <div id="dashboard-toast"
             class="toast border-0 shadow-lg rounded-4 overflow-hidden"
             role="alert"
             aria-live="assertive"
             aria-atomic="true">


            <div class="toast-header bg-danger-subtle border-0 px-3 py-2">


                <i class="bi bi-x-circle-fill text-danger fs-5 me-2"></i>


                <strong class="me-auto text-danger">

                    Error

                </strong>


                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="toast"
                        aria-label="Close">

                </button>


            </div>



            <div class="toast-body bg-white text-dark px-3 py-3">


                {{ session('error') }}


            </div>



        </div>


    @endif


</div>





@if(session()->has('success') || session()->has('error'))

<script>

document.addEventListener('DOMContentLoaded', function(){


    const toastElement =
        document.getElementById('dashboard-toast');


    if(toastElement)

    {


        const toast =
            new bootstrap.Toast(toastElement, {

                delay:4000

            });


        toast.show();


    }


});


</script>

@endif
