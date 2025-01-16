<template>
  <div class="card shadow-lg mx-4">
    <div class="card-body p-3">
      <div class="row gx-4">
        <div class="col-lg-8 col-md-8 my-sm-auto ms-sm-auto m-sm-0 mx-auto mt-3">
          <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1" role="tablist">
              <li class="nav-item">
                <a
                    class="nav-link active"
                    id="pills-home-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-home"
                    type="button"
                    role="tab"
                    aria-controls="pills-home"
                    aria-selected="true"
                >
                  <i class="ni ni-app"></i>
                  <span class="me-2">عامة</span>
                </a>
              </li>
              <li class="nav-item">
                <a
                    class="nav-link"
                    id="pills-app-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-app"
                    type="button"
                    role="tab"
                    aria-controls="pills-app"
                    aria-selected="false"
                >
                  <i class="ni ni-planet"></i>
                  <span class="me-2">Settings App</span>
                </a>
              </li>
              <li class="nav-item">
                <a
                    class="nav-link"
                    id="pills-ads-tab"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-ads"
                    type="button"
                    role="tab"
                    aria-controls="pills-ads"
                    aria-selected="false"
                >
                  <i class="ni ni-planet"></i>
                  <span class="me-2">الاعلانات</span>
                </a>
              </li>
              <li class="nav-item">
                <a
                    class="nav-link"
                    id="pills-ads-cont"
                    data-bs-toggle="pill"
                    data-bs-target="#pills-cont"
                    type="button"
                    role="tab"
                    aria-controls="pills-cont"
                    aria-selected="false"
                >
                  <i class="ni ni-email-83"></i>
                  <span class="me-2">تواصل</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-4">
    <div class="col-lg-12">
      <div class="card mx-4">
        <div class="card-body p-3">
          <div class="tab-content" id="pills-tabContent">
            <div
                class="tab-pane fade show active"
                id="pills-home"
                role="tabpanel"
                aria-labelledby="pills-home-tab"
            >

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_url_android">PlayStore URL</label>
                    <input
                        v-model="settings.app_url_android"
                        id="app_url_android"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_url_ios">AppStore URL</label>
                    <input
                        v-model="settings.app_url_ios"
                        id="app_url_ios"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_privacy">سياسة خصوصية</label>
                    <textarea
                        v-model="settings.app_privacy"
                        id="app_privacy"
                        class="form-control"
                        rows="7"
                        cols="50"
                    >
                    </textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_terms">شروط الاستخدام</label>
                    <textarea
                        v-model="settings.app_terms"
                        id="app_terms"
                        class="form-control"
                        rows="7"
                        cols="50"
                    >
                    </textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_privacy_url">رابط سياسة الخصوصية</label>
                    <input
                        v-model="settings.app_privacy_url"
                        id="app_privacy_url"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="app_terms_url">رابط شروط الاستخدام</label>
                    <input
                        v-model="settings.app_terms_url"
                        id="app_terms_url"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-1">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">
                      اظهار زر مشاهدة
                    </label>
                    <div style="display: block; padding: 0.5rem 0.75rem">
                      <Switch v-model:checked="settings.play_video" labelclass="" />
                    </div>
                  </div>
                </div>
                <div class="col-md-1">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">
                      اظهار قنوات لايف
                    </label>
                    <div style="display: block; padding: 0.5rem 0.75rem">
                      <Switch v-model:checked="settings.en_live" labelclass="" />
                    </div>
                  </div>
                </div>

                <div class="col-md-1">
                  <div class="form-group">
                    <label for="example-text-input" class="form-control-label">
                      اظهار جدول المباريات
                    </label>
                    <div style="display: block; padding: 0.5rem 0.75rem">
                      <Switch v-model:checked="settings.matches_show" labelclass="" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
                class="tab-pane fade"
                id="pills-ads"
                role="tabpanel"
                aria-labelledby="pills-ads-tab"
            >
              <div class="row">
                <div class="lead mb-3">Admob</div>
                <div class="col-sm-6">
                  <div class="lead mb-3">iOS</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_int_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_banner_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان open app
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_open_app_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان مدمج
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.adEnableNativeIos"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="ad_ios_app_id">Ad App ID</label>
                        <input
                            v-model="settings.ad_ios_app_id"
                            id="ad_ios_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_ios_unit_id_open_app">Ad unit Open App ID</label>
                        <input
                            v-model="settings.ad_ios_unit_id_open_app"
                            id="ad_ios_unit_id_open_app"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_ios_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.ad_ios_unit_id_banner"
                            id="ad_ios_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="adIosUnitIdNative">Ad unit Native ID</label>
                        <input
                            v-model="settings.adIosUnitIdNative"
                            id="adIosUnitIdNative"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_ios_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.ad_ios_unit_id_interstitial"
                            id="ad_ios_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="lead mb-3">Android</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_int"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_banner"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.ad_enable_open_app"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان مدمج
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.adEnableNative"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="ad_app_id">Ad App ID</label>
                        <input
                            v-model="settings.ad_app_id"
                            id="ad_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_unit_id_open_app">Ad unit Open App ID</label>
                        <input
                            v-model="settings.ad_unit_id_open_app"
                            id="ad_unit_id_open_app"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.ad_unit_id_banner"
                            id="ad_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="adUnitIdNative">Ad unit Native ID</label>
                        <input
                            v-model="settings.adUnitIdNative"
                            id="adUnitIdNative"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.ad_unit_id_interstitial"
                            id="ad_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="lead mb-3">unityAds</div>
                <div class="col-sm-6">
                  <div class="lead mb-3">iOS</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.unityAds_enable_int_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.unityAds_enable_banner_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="unityAds_app_id">Ad App ID</label>
                        <input
                            v-model="settings.unityAds_ios_app_id"
                            id="unityAds_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="ad_ios_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.unityAds_ios_unit_id_banner"
                            id="ad_ios_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="unityAds_ios_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.unityAds_ios_unit_id_interstitial"
                            id="unityAds_ios_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="lead mb-3">Android</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.unityAds_enable_int"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.unityAds_enable_banner"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="ad_app_id">Ad App ID</label>
                        <input
                            v-model="settings.unityAds_app_id"
                            id="ad_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="unityAds_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.unityAds_unit_id_banner"
                            id="unityAds_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="unityAds_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.unityAds_unit_id_interstitial"
                            id="unityAds_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="lead mb-3">AppLovin</div>
                <div class="col-sm-6">
                  <div class="lead mb-3">iOS</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_int_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_banner_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان open app
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_open_app_ios"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="applovin_ios_app_id">Ad App ID</label>
                        <input
                            v-model="settings.applovin_ios_app_id"
                            id="applovin_ios_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_ios_unit_id_open_app"
                        >Ad unit Open App ID</label
                        >
                        <input
                            v-model="settings.applovin_ios_unit_id_open_app"
                            id="applovin_ios_unit_id_open_app"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_ios_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.applovin_ios_unit_id_banner"
                            id="applovin_ios_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_ios_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.applovin_ios_unit_id_interstitial"
                            id="applovin_ios_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="lead mb-3">Android</div>
                  <div class="row">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بيني
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_int"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_banner"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اعلان بنر
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.applovin_enable_open_app"
                              labelclass="pe-4"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="applovin_app_id">Ad App ID</label>
                        <input
                            v-model="settings.applovin_app_id"
                            id="applovin_app_id"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_unit_id_open_app">Ad unit Open App ID</label>
                        <input
                            v-model="settings.applovin_unit_id_open_app"
                            id="applovin_unit_id_open_app"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_unit_id_banner">Ad unit banner ID</label>
                        <input
                            v-model="settings.applovin_unit_id_banner"
                            id="applovin_unit_id_banner"
                            type="text"
                            class="form-control"
                        />
                      </div>
                      <div class="form-group">
                        <label for="applovin_unit_id_interstitial"
                        >Ad unit interstitial ID</label
                        >
                        <input
                            v-model="settings.applovin_unit_id_interstitial"
                            id="applovin_unit_id_interstitial"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div
                class="tab-pane fade"
                id="pills-cont"
                role="tabpanel"
                aria-labelledby="pills-ads-tab"
            >
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="twitter"> Twitter </label>
                    <input
                        v-model="settings.twitter"
                        id="twitter"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="telegram"> Telegram </label>
                    <input
                        v-model="settings.telegram"
                        id="telegram"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="fb"> Facebook </label>
                    <input
                        v-model="settings.fb"
                        id="fb"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="email"> email </label>
                    <input
                        v-model="settings.email"
                        id="email"
                        type="text"
                        class="form-control"
                    />
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="tab-pane fade" id="pills-tmdb" role="tabpanel" aria-labelledby="pills-tmdb-tab">
                            <div class="settings">
                        <div class="row">
                          <div class="col-md-8">
                            <div class="form-group">
                              <label for="tmdb_api_key">Api key</label>
                              <input
                                class="form-control"
                                id="tmdb_api_key"
                                type="text"
                                v-model="settings.tmdb_api_key"
                              />
                            </div>
                            <div class="form-group">
                              <label for="lang">Language</label>
                              <multiselect v-model="settings.tmdb_lang" :options="langs"  placeholder="اختر الفئة"
      :close-on-select="false"
      :searchable="true"
          track-by="value"
        label="english_name"
        :object="true"
        :hideSelected="true"
        @change="test"

     />

                            </div>
                          </div>
                        </div>
                      </div>

                        </div> -->
            <div
                class="tab-pane fade"
                id="pills-app"
                role="tabpanel"
                aria-labelledby="pills-app-tab"
            >
              <div class="settings">
                <div class="row">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="app_msg_name">عنوان الرسالة</label>
                        <input
                            v-model="settings.app_msg_name"
                            id="app_msg_name"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="app_msg_number">رقم رسالة</label>
                        <input
                            v-model="settings.app_msg_number"
                            id="app_msg_number"
                            type="number"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          اظهار رسالة
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch v-model:checked="settings.show_msg" labelclass="" />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="app_msg_name">الرسالة</label>
                        <Editor
                            v-model="settings.app_msg"
                            api-key="pcctiigojdkquo4na9o2fown0tzuuq6uj928ye4lb1w5gd58"
                            :init="{
                            toolbar_mode: 'sliding',
                            plugins:
                              'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss',
                            toolbar:
                              'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                            tinycomments_mode: 'embedded',
                            tinycomments_author: 'Author name',
                          }"
                            initial-value="Welcome to TinyMCE!"
                        />
                        <!-- <textarea
                          v-model="settings.app_msg"
                          id="app_msg"
                          class="form-control"
                          rows="7"
                          cols="50"
                        >
                        </textarea> -->
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="app_msg_button_link">رابط الرسال</label>
                        <input
                            v-model="settings.app_msg_button_link"
                            id="app_msg_button_link"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="app_msg_name">عنوان الزر</label>
                        <input
                            v-model="settings.app_msg_button_title"
                            id="app_msg_button_title"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <!--update app-->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="app_update_msg_name">عنوان الرسالة</label>
                        <input
                            v-model="settings.app_update_msg_name"
                            id="app_update_msg_name"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="number_version_app">رقم اصدار</label>
                        <input
                            v-model="settings.number_version_app"
                            id="number_version_app"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-1">
                      <div class="form-group">
                        <label for="example-text-input" class="form-control-label">
                          تحديث اجباري
                        </label>
                        <div style="display: block; padding: 0.5rem 0.75rem">
                          <Switch
                              v-model:checked="settings.required_update"
                              labelclass=""
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label for="app_msg_name">الرسالة</label>
                        <textarea
                            v-model="settings.app_update_msg"
                            id="app_msg"
                            class="form-control"
                            rows="7"
                            cols="50"
                        >
                        </textarea>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="app_update_msg_link"> رابط تحميل تحديث</label>
                        <input
                            v-model="settings.app_update_msg_link"
                            id="app_update_msg_link"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <!--end update app-->

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="akwam_domain"> دومين اكوام</label>
                        <input
                            v-model="settings.akwam_domain"
                            id="akwam_domain"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="akwam_domain_sa"> دومين اكوام مصر </label>
                        <input
                            v-model="settings.akwam_domain_eg"
                            id="akwam_domain_sa"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="akwam_domain_sa"> دومين اكوام السعودية </label>
                        <input
                            v-model="settings.akwam_domain_sa"
                            id="akwam_domain_sa"
                            type="text"
                            class="form-control"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="player_external_package">
                        Player External Package
                      </label>
                      <input
                          v-model="settings.player_external_package"
                          id="player_external_package"
                          type="text"
                          class="form-control"
                      />
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label"
                      >Enable Player External
                      </label>
                      <div style="display: block; padding: 0.5rem 0.75rem">
                        <Switch
                            v-model:checked="settings.en_player_external"
                            labelclass=""
                        />
                      </div>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="form-group">
                      <label for="example-text-input" class="form-control-label">
                        تحقق من فير بيس</label
                      >
                      <div style="display: block; padding: 0.5rem 0.75rem">
                        <Switch
                            v-model:checked="settings.firebase_check_app"
                            labelclass=""
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6 my-auto text-end">
            <a class="btn bg-gradient-info mb-0" @click="update()">حفظ </a>

            <!-- <div class="dropdown float-start ps-4">
                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                </a>
                                 <ul class="dropdown-menu px-2 py-3 me-n4" aria-labelledby="dropdownTable">
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="javascript:;">عمل</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="javascript:;">عمل آخر</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item border-radius-md" href="javascript:;">شيء آخر هنا</a>
                                    </li>
                                </ul>
                            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import { notifications } from "../mixins/notifications";
    import Switch from '../core/Switch.vue';

    export default {
        data() {
            return {
                url: url,
                logo: url + "/api/image/logo",
                miniLogo: url + "/api/image/minilogo",
                settings: {}
            };
        },
        async mounted () {
            let response = await axios.get(url+"/admin/player/settings/data")
            for (let index = 0; index < response.data.length; index++) {
                const key = response.data[index].name;
                var value = response.data[index].val;
                if(response.data[index].type == "bool"){
                    value = value == 1 ? true:false;
                }
                this.settings[key] = value;
            }
        },
        methods: {
            // update the settings in the database
            async update() {
                try {
                    // let typs = [];
                    // let settings = [];
                    delete this.settings.types;
                    // delete this.settings.test;

                     const element = this.settings;
                    this.settings.types = Object.keys(element);
                    // for (let index = 0; index < typs.length; index++) {
                    //     settings.push({
                    //         name: typs[index],
                    //         val: this.settings[typs[index]]
                    //     });

                    // }

                    let response = await axios.put(
                        url + "/admin/player/update/settings",
                        this.settings
                    );
                    this.showSuccess(response.data.message);
                } catch (error) {
                    this.showError(error.response);
                }
            },
        },
        mixins: [notifications],
        components: {
            Switch,
        }
    };
    </script>

    <style lang="scss">
    .logo {
        max-width: 100%;

        img {
            max-height: 60px;
        }
    }
    </style>
