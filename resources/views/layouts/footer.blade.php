<footer id="site-footer" class="site-footer background">
    <div class="footer">
        <div class="section-padding">
            <div class="section-container">
                <div class="block-widget-wrap">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="block block-menu m-b-20">
                                <h2 class="block-title">İletişim Bilgileri</h2>
                                <div class="block-content">
                                    <ul>
                                        <li>
                                            <a href="{{route('contact')}}">
                                                {{@$settings['mobile_phone'] ?: ''}}</a>
                                        </li>
                                        <li>
                                            <a href="mailto:{{@$settings['email'] ?: ''}}"> {{@$settings['email'] ?: ''}}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="block block-social">
                                <ul class="social-link">
                                    @if(@$settings['facebook'])
                                        <li><a href="{{@$settings['facebook']}}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    @endif
                                    @if(@$settings['twitter'])
                                        <li><a href="{{@$settings['twitter']}}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                   @endif
                                    @if(@$settings['instagram'])
                                        <li><a href="{{@$settings['instagram']}}" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                    @endif
                                        @if(@$settings['youtube'])
                                            <li><a href="{{@$settings['youtube']}}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                        @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="block block-menu">
                                <h2 class="block-title">Adres Bilgileri</h2>
                                <div class="block-content">
                                    <p>{{@$settings['address'] ?: ''}}</p>
                                </div>
                            </div>
                            <div class="block block-image">
                                <img width="100" height="32" src="{{asset('theme/deekobjects/media/iyzico.png')}}" alt="">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="block block-menu">
                                <h2 class="block-title">Kurumsal</h2>
                                <div class="block-content">
                                    <ul>
                                        @foreach($pages as $page)
                                            <li>
                                                <a href="{{route('contract.sub', ['url' => $page->url])}}">{{$page->title}}</a>
                                            </li>
                                        @endforeach
                                        <li>
                                            <a href="{{route('contact')}}">İletişim</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="section-padding">
            <div class="section-container">
                <div class="block-widget-wrap">
                    <p class="copyright text-center">Copyright © {{\Carbon\Carbon::now()->year}}. Deek Objects</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<a target="_blank" href="https://wa.me/+905{{substr($settings['mobile_phone'],-9)}}?text={{@$settings['whatsap_default_message'] ?: "Merhaba, web sitesinizden ulaşıyorum."}}"
   class="contact-whatsap"
   >
    <img src="{{asset('theme/deekobjects/img/whatsap.png')}}" width="50" height="50"/>
</a>
