<div id="cta">
  @if(Auth::guard('student')->check())
    <div class="cta__wrapper row-center" style="z-index: 99; overflow: hidden;">
      <img src="{{ isset($banner->image_url) ? $banner->image_url:'' }}" class="img-banner__cta">
      <div class="overlay-already-login"></div>
      <div class="row overlay__cta">
        <div class="col-lg-8 col-12 px-5 column-center-start">
          <h1>{{ isset($banner->title) ? $banner->title:'' }}</h1>
          <p class="pt-3">{{ isset($banner->description) ? $banner->description:'' }}</p>
        </div>
        <div class="col-lg-4 col-12 col-12 column-center mt-4 mt-md-0">
          <a href="{{ url('class') }}" class="btn btn-primary row-center btn-animation mt-md-4">
            Daftar Sekarang<img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt="">
          </a>
        </div>
      </div>
    </div>
  @else
    <div class="cta__wrapper row-center" style="z-index: 99; overflow: hidden;">
      <img src="{{ isset($banner->image_url) ? $banner->image_url:'' }}" class="img-banner__cta">
      <div class="overlay"></div>
      <div class="row overlay__cta">
        <div class="col-lg-8 col-12 px-5 column-center-start">
          <h1>{{ isset($banner->title) ? $banner->title:'' }}</h1>
          <p class="pt-3">{{ isset($banner->description) ? $banner->description:'' }}</p>
        </div>
        <div class="col-lg-4 col-12 col-12 column-center mt-4 mt-md-0">
          <a href="{{ url('login') }}" class="btn btn-primary row-center btn-animation mt-md-4">
            Daftar Sekarang<img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}" alt="">
          </a>
        </div>
      </div>
    </div>
  @endif
</div>