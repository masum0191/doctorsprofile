<h4 class="text-center mt-3 mb-4 border-b"><strong>বর্তমান ঠিকানা</strong></h4>

<div class="col-12">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-2 col-form-label">বর্তমান ঠিকানা</label>
        <div class="col-12 col-lg-10">
            <textarea class="form-control" rows="3" name="present_address" id="present_address"></textarea>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">বিভাগ <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="division" id="division" required onchange="populateDistricts('division', 'district')">
                <option value="">বিভাগ নির্বাচন করুন</option>
                <option value="dhaka">ঢাকা</option>
                <option value="chittagong">চট্টগ্রাম</option>
                <option value="khulna">খুলনা</option>
                <option value="barisal">বরিশাল</option>
                <option value="sylhet">সিলেট</option>
                <option value="rajshahi">রাজশাহী</option>
                <option value="rangpur">রংপুর</option>
                <option value="mymensingh">ময়মনসিংহ</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">জেলা <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="district" id="district" required onchange="populateUpazilas('district', 'upazila')">
                <option value="">জেলা নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">উপজেলা/থানা <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="upazila" id="upazila" required onchange="populateUnions('upazila', 'union')">
                <option value="">উপজেলা/থানা নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">ইউনিয়ন <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="union_name" id="union" required>
                <option value="">ইউনিয়ন নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">পোষ্ট অফিস</label>
        <div class="col-12 col-lg-8">
            <input type="text" class="form-control" placeholder="পোষ্ট অফিস লিখুন" name="post_office" id="post_office">
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">ওয়ার্ড নং</label>
        <div class="col-12 col-lg-8">
            {{-- <input type="text" class="form-control" name="ward_number" id="ward_number"> --}}
            <select class="form-control" name="ward_number" id="ward_number" required>
                            <?php $waed = DB::table('wards')->select('name_bn','ward_no')->get(); ?>

                            <option value="">পছন্দ করুন</option>
                            @foreach ($waed as $waed)
                        <option value="{{ @$waed->ward_no }}">{{ @$waed->name_bn }}</option>
                            @endforeach
                        </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">গ্রাম/মহল্লা</label>
        <div class="col-12 col-lg-8">
            <input type="text" class="form-control" placeholder="গ্রাম/মহল্লা লিখুন" name="village_or_area" id="village_or_area">
        </div>
    </div>
</div>

<h4 class="text-center mt-3 mb-4 border-b"><strong>স্থায়ী ঠিকানা</strong></h4>

<div class="col-12">
    <div class="row input-item mb-3">
        <div class="col-12 col-lg-2"></div>
        <div class="col-12 col-lg-10">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="same_as_present" name="same_as_present" onchange="copyPresentToPermanent()">
                <label class="form-check-label" for="same_as_present">বর্তমান ঠিকানার সাথে মিলিয়ে দিন</label>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-2 col-form-label">স্থায়ী ঠিকানা</label>
        <div class="col-12 col-lg-10">
            <textarea class="form-control" rows="3" name="permanent_address" id="permanent_address"></textarea>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">বিভাগ <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="permanent_division" id="permanent_division" required onchange="populateDistricts('permanent_division', 'permanent_district')">
                <option value="">বিভাগ নির্বাচন করুন</option>
                <option value="dhaka">ঢাকা</option>
                <option value="chittagong">চট্টগ্রাম</option>
                <option value="khulna">খুলনা</option>
                <option value="barisal">বরিশাল</option>
                <option value="sylhet">সিলেট</option>
                <option value="rajshahi">রাজশাহী</option>
                <option value="rangpur">রংপুর</option>
                <option value="mymensingh">ময়মনসিংহ</option>


            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">জেলা <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="permanent_district" id="permanent_district" required onchange="populateUpazilas('permanent_district', 'permanent_upazila')">
                <option value="">জেলা নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">উপজেলা/থানা <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="permanent_upazila" id="permanent_upazila" required onchange="populateUnions('permanent_upazila', 'permanent_union')">
                <option value="">উপজেলা/থানা নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">ইউনিয়ন <span>*</span></label>
        <div class="col-12 col-lg-8">
            <select class="form-select" name="permanent_union" id="permanent_union" required>
                <option value="">ইউনিয়ন নির্বাচন করুন</option>
            </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">পোষ্ট অফিস</label>
        <div class="col-12 col-lg-8">
            <input type="text" class="form-control" placeholder="পোষ্ট অফিস লিখুন" name="permanent_post_office" id="permanent_post_office">
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">ওয়ার্ড নং</label>
        <div class="col-12 col-lg-8">
            {{-- <input type="text" class="form-control" name="permanent_ward_number" id="permanent_ward_number"> --}}
             <select class="form-control" name="permanent_ward_number" id="permanent_ward_number">
                    <?php $waed1 = DB::table('wards')->select('name_bn','ward_no')->get(); ?>
                            <option value="">পছন্দ করুন</option>
                            @foreach ($waed1 as $waed)
                        <option value="{{ @$waed->ward_no }}">{{ @$waed->name_bn }}</option>
                            @endforeach
                        </select>
        </div>
    </div>
</div>

<div class="col-12 col-lg-6">
    <div class="row input-item mb-3">
        <label class="col-12 col-lg-4 col-form-label">গ্রাম/মহল্লা</label>
        <div class="col-12 col-lg-8">
            <input type="text" class="form-control" placeholder="গ্রাম/মহল্লা লিখুন" name="permanent_village_or_area" id="permanent_village_or_area">
        </div>
    </div>
</div>

<script>
// Complete address data for Bangladesh
const addressData = {
    districts: {
        'dhaka': ['ঢাকা', 'গাজীপুর', 'নারায়ণগঞ্জ', 'মানিকগঞ্জ', 'মুন্সিগঞ্জ', 'নরসিংদী', 'টাঙ্গাইল', 'ফরিদপুর', 'গোপালগঞ্জ', 'মাদারীপুর', 'শরীয়তপুর', 'রাজবাড়ী'],
        'chittagong': ['চট্টগ্রাম', 'কক্সবাজার', 'রাঙ্গামাটি', 'বান্দরবান', 'খাগড়াছড়ি', 'কুমিল্লা', 'ফেনী', 'নোয়াখালী', 'লক্ষ্মীপুর', 'চাঁদপুর', 'ব্রাহ্মণবাড়িয়া'],
        'khulna': ['খুলনা', 'বাগেরহাট', 'সাতক্ষীরা', 'যশোর', 'নড়াইল', 'মাগুরা', 'কুষ্টিয়া', 'মেহেরপুর', 'চুয়াডাঙ্গা', 'ঝিনাইদহ'],
        'barisal': ['বরিশাল', 'পটুয়াখালী', 'ভোলা', 'পিরোজপুর', 'ঝালকাঠি', 'বরগুনা'],
        'sylhet': ['সিলেট', 'মৌলভীবাজার', 'হবিগঞ্জ', 'সুনামগঞ্জ'],
        'rajshahi': ['রাজশাহী', 'নাটোর', 'নওগাঁ', 'চাঁপাইনবাবগঞ্জ', 'পাবনা', 'সিরাজগঞ্জ', 'বগুড়া', 'জয়পুরহাট'],
        'rangpur': ['রংপুর', 'দিনাজপুর', 'নীলফামারী', 'গাইবান্ধা', 'কুড়িগ্রাম', 'লালমনিরহাট', 'ঠাকুরগাঁও', 'পঞ্চগড়'],
        'mymensingh': ['ময়মনসিংহ', 'শেরপুর', 'নেত্রকোণা', 'জামালপুর']
    },
    upazilas: {
        'ঢাকা': ['সদর', 'দোহার', 'কেরানীগঞ্জ', 'সাভার', 'ধামরাই', 'নবাবগঞ্জ'],
        'গাজীপুর': ['গাজীপুর সদর', 'কালিয়াকৈর', 'কালিগঞ্জ', 'কাপাসিয়া', 'শ্রীপুর'],
        'নারায়ণগঞ্জ': ['নারায়ণগঞ্জ সদর', 'ফতুল্লা', 'সোনারগাঁও', 'আড়াইহাজার', 'রূপগঞ্জ'],
        'মানিকগঞ্জ': ['মানিকগঞ্জ সদর', 'সিংগাইর', 'শিবালয়', 'হরিরামপুর', 'ঘিওর'],
        'মুন্সিগঞ্জ': ['মুন্সিগঞ্জ সদর', 'শ্রীনগর', 'সিরাজদিখান', 'লৌহজং', 'টংগিবাড়ী'],
        'নরসিংদী': ['নরসিংদী সদর', 'বেলাবো', 'মনোহরদী', 'পলাশ', 'রায়পুরা', 'শিবপুর'],

        'চট্টগ্রাম': ['চট্টগ্রাম সদর', 'পটিয়া', 'বোয়ালখালী', 'সীতাকুণ্ড', 'মীরসরাই', 'রাঙ্গুনিয়া', 'সন্দ্বীপ', 'সাতকানিয়া'],
        'কক্সবাজার': ['কক্সবাজার সদর', 'উখিয়া', 'টেকনাফ', 'রামু', 'মহেশখালী', 'চকরিয়া', 'পেকুয়া'],
        'রাঙ্গামাটি': ['রাঙ্গামাটি সদর', 'কাপ্তাই', 'কাউখালী', 'বাঘাইছড়ি', 'বরকল', 'লংগদু', 'রাজস্থলী'],
        'বান্দরবান': ['বান্দরবান সদর', 'আলীকদম', 'নাইক্ষ্যংছড়ি', 'রোয়াংছড়ি', 'রুমা', 'থানচি'],
        'খাগড়াছড়ি': ['খাগড়াছড়ি সদর', 'দিঘীনালা', 'পানছড়ি', 'লক্ষীছড়ি', 'মহালছড়ি', 'মাটিরাঙ্গা'],

        'খুলনা': ['খুলনা সদর', 'ডুমুরিয়া', 'বটিয়াঘাটা', 'দাকোপ', 'কয়রা', 'ফুলতলা', 'রূপসা'],
        'বাগেরহাট': ['বাগেরহাট সদর', 'মোড়েলগঞ্জ', 'কচুয়া', 'মোংলা', 'চিতলমারী', 'ফকিরহাট', 'রামপাল'],
        'সাতক্ষীরা': ['সাতক্ষীরা সদর', 'কলারোয়া', 'তালা', 'কালিগঞ্জ', 'দেবহাটা', 'আশাশুনি', 'শ্যামনগর'],
        'যশোর': ['যশোর সদর', 'চৌগাছা', 'ঝিকরগাছা', 'বাঘারপাড়া', 'কেশবপুর', 'মনিরামপুর', 'শার্শা'],

        'বরিশাল': ['বরিশাল সদর', 'বাকেরগঞ্জ', 'বাবুগঞ্জ', 'উজিরপুর', 'গৌরনদী', 'মেহেন্দিগঞ্জ', 'মুলাদী'],
        'পটুয়াখালী': ['পটুয়াখালী সদর', 'দশমিনা', 'কলাপাড়া', 'মির্জাগঞ্জ', 'গলাচিপা', 'রাঙ্গাবালী'],
        'ভোলা': ['ভোলা সদর', 'দৌলতখান', 'বোরহানউদ্দিন', 'তজুমদ্দিন', 'লালমোহন', 'চরফ্যাশন'],

        'সিলেট': ['সিলেট সদর', 'বিয়ানীবাজার', 'গোলাপগঞ্জ', 'কোম্পানীগঞ্জ', 'জৈন্তাপুর', 'কানাইঘাট', 'জকিগঞ্জ'],
        'মৌলভীবাজার': ['মৌলভীবাজার সদর', 'বড়লেখা', 'কুলাউড়া', 'শ্রীমঙ্গল', 'জুড়ী', 'রাজনগর'],
        'হবিগঞ্জ': ['হবিগঞ্জ সদর', 'নবীগঞ্জ', 'বাহুবল', 'আজমিরীগঞ্জ', 'লাখাই', 'চুনারুঘাট', 'মাধবপুর'],

        'রাজশাহী': ['রাজশাহী সদর', 'পুঠিয়া', 'দুর্গাপুর', 'মোহনপুর', 'চারঘাট', 'বাঘা', 'তানোর'],
        'নাটোর': ['নাটোর সদর', 'সিংড়া', 'বড়াইগ্রাম', 'গুরুদাসপুর', 'লালপুর', 'বাগাতিপাড়া'],
        'বগুড়া': ['বগুড়া সদর', 'শেরপুর', 'ধুনট', 'দুপচাচিয়া', 'গাবতলী', 'কাহালু', 'নন্দীগ্রাম'],

        'রংপুর': ['রংপুর সদর', 'গংগাচড়া', 'তারাগঞ্জ', 'বদরগঞ্জ', 'মিঠাপুকুর', 'পীরগঞ্জ', 'কাউনিয়া'],
        'দিনাজপুর': ['দিনাজপুর সদর', 'পার্বতীপুর', 'ফুলবাড়ী', 'বিরামপুর', 'খানসামা', 'বিরল', 'চিরিরবন্দর'],
        'নীলফামারী': ['নীলফামারী সদর', 'ডিমলা', 'ডোমার', 'জলঢাকা', 'কিশোরগঞ্জ'],

        'ময়মনসিংহ': ['ময়মনসিংহ সদর', 'ফুলবাড়ীয়া', 'ত্রিশাল', 'ভালুকা', 'মুক্তাগাছা', 'ধোবাউড়া', 'ফুলপুর'],
        'শেরপুর': ['শেরপুর সদর', 'নালিতাবাড়ী', 'শ্রীবরদী', 'ঝিনাইগাতী', 'নকলা'],
        'নেত্রকোণা': ['নেত্রকোণা সদর', 'মদন', 'মোহনগঞ্জ', 'বারহাট্টা', 'আটপাড়া', 'কলমাকান্দা', 'খালিয়াজুড়ি'],
        'জামালপুর': ['জামালপুর সদর', 'মেলান্দহ', 'ইসলামপুর', 'দেওয়ানগঞ্জ', 'বকশীগঞ্জ']
    },
    unions: {
        'সদর': ['মোহাম্মদপুর', 'মিরপুর', 'পল্লবী', 'কাফরুল', 'শেরে বাংলা নগর'],
        'দোহার': ['দোহার সদর', 'কুশুরা', 'নয়নশ্রী', 'সুতালড়ী', 'মুকসুদপুর'],
        'কেরানীগঞ্জ': ['কেরানীগঞ্জ সদর', 'কালীগঞ্জ', 'বাস্তা', 'শুভাঢ্যা', 'তেঁতুলঝোড়া'],
        'সাভার': ['সাভার সদর', 'আশুলিয়া', 'শিমুলিয়া', 'কাউন্দিয়া', 'বিরুলিয়া'],
        'ধামরাই': ['ধামরাই সদর', 'সুবিল', 'কুল্লা', 'রোয়াইল', 'কুসুমহাটি'],

        'চট্টগ্রাম সদর': ['হালিশহর', 'আগ্রাবাদ', 'বকশিরহাট', 'পাহাড়তলী', 'চান্দগাঁও'],
        'পটিয়া': ['পটিয়া সদর', 'জঙ্গলখাইন', 'ধলঘাট', 'শোভনদণ্ডী', 'হাইদগাঁও'],
        'বোয়ালখালী': ['বোয়ালখালী সদর', 'আমুচিয়া', 'শাকপুরা', 'সরল', 'পোপাদিয়া'],

        'কক্সবাজার সদর': ['কক্সবাজার পৌরসভা', 'জালালাবাদ', 'খুরুশকুল', 'পোকখালী', 'ঈদগাঁও'],
        'উখিয়া': ['উখিয়া সদর', 'হোয়াইক্যং', 'রত্নাপালং', 'জালিয়াপালং', 'পালংখালী'],

        'খুলনা সদর': ['খালিশপুর', 'বয়রা', 'দৌলতপুর', 'খালিশপুর', 'সোনাডাঙ্গা'],
        'ডুমুরিয়া': ['ডুমুরিয়া সদর', 'মাগুরঘোনা', 'শরাফপুর', 'রুদাঘরা', 'শেখেরকোল'],

        'বরিশাল সদর': ['বরিশাল পৌরসভা', 'বড়েয়াকাঠী', 'বাসন্ডা', 'বিয়ানীবাজার', 'গৌরনদী'],
        'বাকেরগঞ্জ': ['বাকেরগঞ্জ সদর', 'চাঁদপাই', 'গাবখান', 'চরবানিয়ারী', 'কাশিপুর'],

        'সিলেট সদর': ['সিলেট পৌরসভা', 'টুকেরবাজার', 'খাদিমনগর', 'জালালাবাদ', 'মোগলাবাজার'],
        'বিয়ানীবাজার': ['বিয়ানীবাজার সদর', 'আলীনগর', 'দৌলতপুর', 'ছাতক', 'মুরাদপুর'],

        'রাজশাহী সদর': ['রাজশাহী পৌরসভা', 'কাটাখালী', 'শাহমখদুম', 'বোয়ালিয়া', 'বাগমারা'],
        'পুঠিয়া': ['পুঠিয়া সদর', 'বেলপুকুরিয়া', 'বেলপুকুরিয়া', 'মালঞ্চী', 'জিউপাড়া'],

        'রংপুর সদর': ['রংপুর পৌরসভা', 'শাহীদাগঞ্জ', 'কান্তপুর', 'মিঠাপুকুর', 'হারাগাছ'],
        'গংগাচড়া': ['গংগাচড়া সদর', 'মোহনপুর', 'ঘোড়াঘাট', 'সারাই', 'পীরগঞ্জ'],

        'ময়মনসিংহ সদর': ['ময়মনসিংহ পৌরসভা', 'বক্তপুর', 'আমতলা', 'বিরিশিরি', 'ধোবাউড়া'],
        'ফুলবাড়ীয়া': ['ফুলবাড়ীয়া সদর', 'কালিয়াকৈর', 'ধুনট', 'মুক্তাগাছা', 'ভালুকা']
    }
};

// Function to populate districts based on division selection
function populateDistricts(divisionId, districtId) {
    const divisionSelect = document.getElementById(divisionId);
    const districtSelect = document.getElementById(districtId);
    const divisionValue = divisionSelect.value;

    // Clear previous options
    districtSelect.innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';

    if (divisionValue && addressData.districts[divisionValue]) {
        addressData.districts[divisionValue].forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            districtSelect.appendChild(option);
        });
    }

    // Clear dependent fields
    const upazilaId = districtId.replace('district', 'upazila');
    const unionId = districtId.replace('district', 'union');
    document.getElementById(upazilaId).innerHTML = '<option value="">উপজেলা/থানা নির্বাচন করুন</option>';
    document.getElementById(unionId).innerHTML = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
}

// Function to populate upazilas based on district selection
function populateUpazilas(districtId, upazilaId) {
    const districtSelect = document.getElementById(districtId);
    const upazilaSelect = document.getElementById(upazilaId);
    const districtValue = districtSelect.value;

    // Clear previous options
    upazilaSelect.innerHTML = '<option value="">উপজেলা/থানা নির্বাচন করুন</option>';

    if (districtValue && addressData.upazilas[districtValue]) {
        addressData.upazilas[districtValue].forEach(upazila => {
            const option = document.createElement('option');
            option.value = upazila;
            option.textContent = upazila;
            upazilaSelect.appendChild(option);
        });
    }

    // Clear dependent field
    const unionId = upazilaId.replace('upazila', 'union');
    document.getElementById(unionId).innerHTML = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
}

// Function to populate unions based on upazila selection
function populateUnions(upazilaId, unionId) {
    const upazilaSelect = document.getElementById(upazilaId);
    const unionSelect = document.getElementById(unionId);
    const upazilaValue = upazilaSelect.value;

    // Clear previous options
    unionSelect.innerHTML = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';

    if (upazilaValue && addressData.unions[upazilaValue]) {
        addressData.unions[upazilaValue].forEach(union => {
            const option = document.createElement('option');
            option.value = union;
            option.textContent = union;
            unionSelect.appendChild(option);
        });
    }
}

// Function to copy present address to permanent address
// Function to copy present address to permanent address
function copyPresentToPermanent() {
    const isChecked = document.getElementById('same_as_present').checked;

    // Copy text fields
    const presentAddress = document.getElementById('present_address');
    const permanentAddress = document.getElementById('permanent_address');
    if (presentAddress && permanentAddress) {
        permanentAddress.value = isChecked ? presentAddress.value : '';
        permanentAddress.readOnly = isChecked;
    }

    // Copy select fields (division, district, upazila, union)
    const selectFields = ['division', 'district', 'upazila', 'union'];

    selectFields.forEach(field => {
        const presentField = document.getElementById(field);
        const permanentField = document.getElementById('permanent_' + field);

        if (presentField && permanentField) {
            if (isChecked) {
                // Set the value
                permanentField.value = presentField.value;

                // For division, also populate the districts
                if (field === 'division') {
                    populateDistricts('permanent_division', 'permanent_district');
                    // After districts are populated, set the district value
                    setTimeout(() => {
                        document.getElementById('permanent_district').value = document.getElementById('district').value;
                        // Then populate upazilas
                        populateUpazilas('permanent_district', 'permanent_upazila');
                        setTimeout(() => {
                            document.getElementById('permanent_upazila').value = document.getElementById('upazila').value;
                            // Then populate unions
                            populateUnions('permanent_upazila', 'permanent_union');
                            setTimeout(() => {
                                document.getElementById('permanent_union').value = document.getElementById('union').value;
                            }, 100);
                        }, 100);
                    }, 100);
                }

                // Make select fields readonly by disabling all options except the selected one
                if (permanentField.tagName === 'SELECT') {
                    Array.from(permanentField.options).forEach(option => {
                        option.disabled = option.value !== permanentField.value && option.value !== '';
                    });
                }
            } else {
                // Enable all options when unchecked
                if (permanentField.tagName === 'SELECT') {
                    Array.from(permanentField.options).forEach(option => {
                        option.disabled = false;
                    });
                }

                // Reset value if needed
                if (field === 'division') {
                    permanentField.value = '';
                    document.getElementById('permanent_district').innerHTML = '<option value="">জেলা নির্বাচন করুন</option>';
                    document.getElementById('permanent_upazila').innerHTML = '<option value="">উপজেলা/থানা নির্বাচন করুন</option>';
                    document.getElementById('permanent_union').innerHTML = '<option value="">ইউনিয়ন নির্বাচন করুন</option>';
                }
            }
        }
    });

    // Copy other fields
    const otherFields = ['post_office', 'ward_number', 'village_or_area'];
    otherFields.forEach(field => {
        const presentField = document.getElementById(field);
        const permanentField = document.getElementById('permanent_' + field);

        if (presentField && permanentField) {
            permanentField.value = isChecked ? presentField.value : '';
            permanentField.readOnly = isChecked;
        }
    });
}
</script>
