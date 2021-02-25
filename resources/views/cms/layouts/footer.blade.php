<footer>
  <div class="footer-overlay pb-5">
    <div class="row">
      <div class="col-lg-4 col-md-6 px-3 pr-md-5">
        <div class="badge__wrapper pb-4 pt-md-0">
          <img class="logo mr-2" src="{{ asset('cms/assets/img/logo.png') }}" alt="">
          <h1>mejaseni</h1>
        </div>
        <div class="mt-4">
          <h5>{{ $company->name ? $company->name :'' }}</h5>
          <p class="pt-2">{{ $company->address ? $company->address :'' }}</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 px-3 pr-md-5 mt-md-4">
        @foreach ($branchs as $branch)
          @if ($loop->index == 0)
            <div>
              <h5 class="mb-2 mt-5 mt-md-0">{{ $branch->name ? $branch->name :'' }}</h5>
              <p>{{ $branch->address ? $branch->address :'' }}</p>
            </div>
          @else
            <div class="mt-4">
              <h5 class="mb-2 mt-5 mt-md-0">{{ $branch->name ? $branch->name :'' }}</h5>
              <p>{{ $branch->address ? $branch->address :'' }}</p>
            </div>
          @endif
        @endforeach
        
        
      </div>
      <div class="col-lg-2 col-md-6 px-3 pr-md-5 mt-md-4">
        <h5 class="mb-2 mt-5 mt-md-0">Company</h5>
        <ul class="mt-4">
          <li>
            <a href="{{ url('about') }}">Tentang Kami</a>
          </li>
          <li>
            <a href="{{ url('privacy-policy') }}">Kebijakan Privasi</a>
          </li>
          <li>
            <a href="{{ url('tos') }}">Syarat Layanan</a>
          </li>
          <li>
            <a href="{{ url('faq') }}">FAQ</a>
          </li>
          <li>
            <a href="{{ url('career') }}">Karir</a>
          </li>
        </ul>
      </div>
      <div class="col-lg-3 col-md-6 px-3 pr-md-5 mt-md-4">
        <div class="mt-5 mt-md-0">
          <div class="badge__wrapper mb-2">
            <img class="image-badge__footer mr-2" src="{{ asset('cms/assets/img/svg/Call.svg') }}" alt="">
            <h5>Call Us</h5>
          </div>
          <p>0251 – 8417382 (Headquarters)</p>
          <p>0274 – 4295095 (Branch Office)</p>
          <p>{{ $company->telephone ? $company->telephone:'' }}</p>
        </div>
        <div class="mt-4">
          <div class="badge__wrapper mb-2">
            <img class="image-badge__footer mr-2" src="{{ asset('cms/assets/img/svg/Mail.svg') }}" alt="">
            <h5>Email</h5>
          </div>
          <a href="{{ $company->email ? $company->email:'#' }}">{{ $company->email ? $company->email:'' }}</a>
        </div>
        <div class="mt-5 mt-md-4">
          <div class="badge__wrapper mb-2">
            <h5>Social Media</h5>
          </div>
          <div class="badge__wrapper pt-2" id="social-media__wrapper">
            <a target="_blank" href="#">
              <img src="{{ asset('cms/assets/img/instagram-logo.png') }}" alt="">
            </a>
            <a target="_blank" href="#">
              <img src="{{ asset('cms/assets/img/svg/logo-facebook.svg') }}" alt="">
            </a>
            <a target="_blank" href="#">
              <img src="{{ asset('cms/assets/img/logo-linkedin.png') }}" alt="">
            </a>
            <a target="_blank" href="#">
              <img src="{{ asset('cms/assets/img/svg/logo-twitter.svg') }}" alt="">
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</footer>