<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'user_id' => 2,
            'location_id' => 1,
            'title' => 'Du lịch Hạ Long: Khám phá những địa danh và món ngon nổi tiếng nhất',
            'content' => <<<HTML
<h2>Du lịch Hạ Long: Khám phá những địa danh và món ngon nổi tiếng nhất</h2>
<p><strong>1. Giới thiệu Hạ Long</strong><br>
Hạ Long, thuộc tỉnh Quảng Ninh, cách Hà Nội 180km, là thiên đường du lịch phía Bắc. Với cơ sở hạ tầng hiện đại và giao thông phát triển, Hạ Long thu hút hàng triệu lượt khách mỗi năm. Vịnh Hạ Long, diện tích 1.553km² với 1.900 đảo đá vôi, được UNESCO công nhận là kỳ quan thiên nhiên thế giới, là điểm đến không thể bỏ qua.</p>
<p><strong>2. Vì sao nên du lịch Hạ Long?</strong><br>
- <strong>Kỳ quan thiên nhiên thế giới</strong>: Là một trong 7 kỳ quan, vịnh Hạ Long thu hút du khách toàn cầu với cảnh quan độc đáo.<br>
- <strong>Cơ sở vật chất hiện đại</strong>: Hạ Long có hệ thống khách sạn, resort, dịch vụ chất lượng, đáp ứng nhu cầu nghỉ dưỡng, mua sắm.<br>
- <strong>Điểm tham quan đa dạng</strong>: Các địa điểm như vịnh Hạ Long, phố cổ, chợ đêm, Tuần Châu mang đến trải nghiệm phong phú.</p>
<img src="https://statics.vinpearl.com/kinh-nghiem-du-lich-ha-long-2_1674039281.jpg" alt="Vịnh Hạ Long">
<p><strong>3. Nên du lịch Hạ Long tháng nào?</strong><br>
- <strong>Tháng 4-6</strong>: Thời tiết ấm, không mưa, lý tưởng cho tham quan và nghỉ dưỡng.<br>
- <strong>Tháng 6-8</strong>: Mùa cao điểm, đông khách, giá dịch vụ cao, dễ có bão vào tháng 7-8, cần theo dõi thời tiết.<br>
- <strong>Cuối tháng 11 đến Tết</strong>: Yên bình, phù hợp với khách nước ngoài và những ai muốn không gian riêng tư.</p>
<img src="https://statics.vinpearl.com/20231204_PAVILION05232_1746775411.jpg" alt="Cảnh biển Hạ Long">
<p><strong>4. Trải nghiệm độc đáo tại Hạ Long</strong><br>
- <strong>Chèo thuyền Kayak</strong>: Tự chèo giữa vịnh, cảm nhận thiên nhiên hùng vĩ, lưu ý giữ cân bằng khi chèo.<br>
- <strong>Ngủ đêm trên du thuyền</strong>: Trải nghiệm bình minh giữa biển, tránh xa khói bụi đô thị.<br>
- <strong>Ngắm vịnh từ thủy phi cơ</strong>: Xem toàn cảnh Hạ Long từ trên cao, mang lại góc nhìn ấn tượng.</p>
<img src="https://statics.vinpearl.com/vinh-bai-tu-long-ngo-ngang-canh-sac-tua-chon-bong-lai-tien-canh-3-1641549879_1688286593.jpeg" alt="Vịnh Bái Tử Long">
<p><strong>5. Gợi ý khách sạn và đặt phòng</strong><br>
Hạ Long có nhiều lựa chọn từ nhà nghỉ bình dân đến resort cao cấp. Nổi bật là <strong>Vinpearl Resort & Spa Hạ Long</strong> trên đảo Rều, thiết kế như lâu đài hoàng gia, toàn bộ phòng hướng biển. Du khách tận hưởng tiện ích 5 sao, ngắm bình minh và hoàng hôn lãng mạn.</p>
<img src="https://statics.vinpearl.com/kinh-nghiem-du-lich-ha-long-23_1674039596.jpg" alt="Vinpearl Resort & Spa Hạ Long">
HTML,
            'status' => 'approved',
            'image' => '/image/halong_tour.jpg',
        ]);

        Post::create([
            'user_id' => 5,
            'location_id' => 3,
            'title' => 'Kinh nghiệm du lịch Sapa chi tiết A - Z mới nhất 2025',
            'content' => <<<HTML
<h2>Kinh nghiệm du lịch Sapa chi tiết A - Z mới nhất 2025</h2>
<p>Sapa, thị trấn sương mù ở Lào Cai, nằm ở độ cao 1.500-1.600m, cách Hà Nội 376km, nổi tiếng với khí hậu ôn đới và cảnh quan núi non hùng vĩ. Đây là điểm đến lý tưởng cho du khách yêu thiên nhiên, với tuyết rơi hiếm hoi vào mùa đông.</p>
<p><strong>1. Thời điểm lý tưởng để du lịch Sapa</strong><br>
Sapa đẹp quanh năm với nhiệt độ hiếm khi vượt 25°C, mỗi mùa mang vẻ đặc trưng:<br>
- <strong>Mùa xuân (tháng 12-4)</strong>: Hoa đào nở rộ (tháng 12-2), hoa mai, mận, ban, tam giác mạch (tháng 2-4) phủ sắc hồng, trắng rực rỡ.<br>
- <strong>Mùa hè (tháng 4-8)</strong>: Mùa nước đổ (tháng 4-5) với ruộng lúa non xanh mướt, mùa lúa chín (tháng 8-9) vàng rực dưới ánh bình minh/hoàng hôn.<br>
- <strong>Mùa thu (tháng 10-11)</strong>: Hoa tam giác mạch nở hồng rực, đặc biệt Lễ hội hoa Tam giác mạch cuối tháng 10.<br>
- <strong>Mùa đông (tháng 11-2)</strong>: Tuyết rơi hiếm hoi, hoa cải nở rộ, nhưng thời tiết lạnh dưới 0°C, cần chuẩn bị quần áo ấm.</p>
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-1.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Hoa xuân ở Sapa">
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-2.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Ruộng bậc thang mùa hè">
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-3.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Hoa tam giác mạch mùa thu">
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-4.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Tuyết rơi mùa đông ở Sapa">
<p><strong>2. Đến Sapa bằng cách nào?</strong><br>
Sapa cách Hà Nội 376km, thường du khách bay đến Hà Nội (vé từ TP.HCM/Đà Nẵng: 1.300.000-2.000.000 VND/chiều), sau đó di chuyển đến Sapa hoặc qua Lào Cai:<br>
- <strong>Đi thẳng Sapa</strong>:<br>
  - Xe khách: Hãng Sao Việt, Sapa Express, Inter Bus Line, giá 200.000-400.000 VND/lượt.<br>
  - Tàu hỏa: Hãng Sapaly, Chapa, King Express, Victoria Express, giá 380.000-1.700.000 VND/lượt.<br>
- <strong>Đi Lào Cai rồi đến Sapa</strong>: Tàu hỏa đến Lào Cai (150.000-500.000 VND/chiều), sau đó đi xe buýt/xe khách (45 phút).<br>
- <strong>Xe máy</strong>: Phù hợp với người thích phượt, nhưng cần cẩn thận vì đường đèo dốc, giúp tiết kiệm chi phí.</p>
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-8.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Cung đường đến Sapa">
<p><strong>3. Nên ở đâu khi đến Sapa?</strong><br>
Sapa có đa dạng lựa chọn lưu trú từ khách sạn, resort đến homestay:<br>
- <strong>Khách sạn</strong>: Sapa Hills, Golden Villa Hotel, Sapa Odyssey Hotel, giá hợp lý. Gợi ý trên Traveloka:<br>
  - KK Sapa Hotel: từ 1.449.700 VND/phòng/đêm.<br>
  - DeLaSol Phat Linh Sapa: từ 762.638 VND/phòng/đêm.<br>
  - Pao's Sapa Leisure Hotel: từ 1.739.000 VND/phòng/đêm.<br>
- <strong>Resort</strong>: Sapa Jade Hill Resort, Topas Ecolodge, Aira Boutique Sapa Hotel & Spa, lý tưởng cho nghỉ dưỡng sang trọng.<br>
- <strong>Homestay/Hostel</strong>: Sapa Capsule Hotel, Phori’s House, Little Sapa, Po Homestay, phù hợp du lịch bụi.</p>
<img src="https://cdn.vntrip.vn/cam-nang/wp-content/uploads/2017/10/Sapa-Jade-Hill-e1508314536764.png" alt="Sapa Jade Hill Resort">
<img src="https://ik.imagekit.io/tvlk/blog/2017/01/kinh-nghiem-du-lich-sa-pa-tu-tuc-27.jpg?tr=q-70,c-at_max,w-500,h-300,dpr-2" alt="Resort ở Sapa">
HTML,
            'status' => 'approved',
            'image' => '/image/sapa1.jpg'
        ]);

        Post::create([
            'user_id' => 5,
            'location_id' => 6,
            'title' => 'Nha Trang – Thiên đường nghỉ dưỡng',
            'content' => <<<HTML
<h2>Nha Trang – Thiên đường nghỉ dưỡng</h2>
<p>Nha Trang, thành phố biển nổi tiếng của Khánh Hòa, chào đón du khách bằng ánh nắng vàng rực rỡ và bãi biển xanh biếc trải dài. Với khí hậu ôn hòa và cảnh quan tuyệt đẹp, đây là điểm đến lý tưởng cho những ai yêu thích nghỉ dưỡng và khám phá biển đảo.</p>
<p><strong>1. Trải nghiệm không thể bỏ lỡ ở Nha Trang</strong><br>
- <strong>Tắm biển và nghỉ dưỡng</strong>: Các bãi biển như Bãi Dài, Bãi Trũ đẹp mê hồn, lý tưởng để thư giãn. Nghỉ tại resort sát biển với spa cao cấp mang lại cảm giác sang trọng.<br>
- <strong>Khám phá đảo</strong>: Đi tàu đáy kính ở Hòn Mun để ngắm san hô rực rỡ hoặc tham quan VinWonders với các trò chơi giải trí hiện đại.<br>
- <strong>Tham quan di tích</strong>: Tháp Bà Ponagar là điểm đến văn hóa, nơi bạn tìm hiểu về lịch sử Chăm Pa.</p>
<img src="https://xaviahotel.com/vnt_upload/news/11_2017/nha-trang_1.jpg" alt="Bãi biển Nha Trang">
<p><strong>2. Ẩm thực Nha Trang</strong><br>
Nha Trang nổi tiếng với hải sản tươi sống, đặc biệt là tôm hùm, mực nướng, và sò điệp. Nem nướng Ninh Hòa, ăn kèm rau sống và nước chấm đậm đà, là món ăn dân dã không thể bỏ qua. Chợ đêm Nha Trang là nơi lý tưởng để thưởng thức ẩm thực và mua quà lưu niệm.</p>
<img src="https://baokhanhhoa.vn/file/e7837c02857c8ca30185a8c39b582c03/042023/image001_20230429100214.jpg" alt="Hải sản Nha Trang">
<p><strong>3. Gợi ý lưu trú</strong><br>
Nha Trang có nhiều resort và khách sạn từ bình dân đến cao cấp. Gợi ý:<br>
- <strong>Vinpearl Resort Nha Trang</strong>: Nằm trên đảo Hòn Tre, cung cấp tiện ích 5 sao, giá từ 2.000.000 VND/phòng/đêm.<br>
- <strong>Amiana Resort</strong>: Không gian yên tĩnh, giá từ 1.800.000 VND/phòng/đêm.<br>
- <strong>Homestay gần biển</strong>: Phù hợp du lịch bụi, giá từ 300.000 VND/phòng/đêm.</p>
<img src="https://hotdeal.vn/images/uploads/2017/Th%C3%A1ng%202/14/318297/318297-vinpearl-nha-trang-body%20%281%29.jpg" alt="Vinpearl Resort Nha Trang">
HTML,
            'status' => 'approved',
            'image' => '/image/nhatrang_tour.jpg',
        ]);

        Post::create([
            'user_id' => 5,
            'location_id' => 2,
            'title' => 'Ẩm thực Đà Nẵng không thể bỏ lỡ',
            'content' => <<<HTML
<h2>Ẩm thực Đà Nẵng không thể bỏ lỡ</h2>
<p>Đà Nẵng, thành phố biển miền Trung, không chỉ nổi tiếng với cảnh quan tuyệt đẹp mà còn là thiên đường ẩm thực với các món ăn đậm đà bản sắc. Người dân thân thiện và giá cả hợp lý khiến trải nghiệm ẩm thực nơi đây càng thêm đáng nhớ.</p>
<p><strong>1. Các món ngon phải thử</strong><br>
- <strong>Mì Quảng</strong>: Nhắc đến ẩm thực Đà Nẵng, không thể bỏ qua món mì Quảng ngon trứ danh. Đây là món ăn dân dã mà bất cứ ai cũng đều thích sau lần thử đầu tiên. Một tô mì Quảng vàng thơm với sợi mì mềm, dai được làm từ bột gạo, bên cạnh đó không thể thiếu chính là hương vị ngọt thanh, thơm nhè nhẹ của nước lèo được ninh từ xương trong nhiều giờ. Một tô mì Quảng đầy đủ bao gồm mì, thịt heo hoặc thịt gà, tôm, trứng,… Ăn mì Quảng kèm với rau sống, bánh đa, chanh, ớt,….<br>
<img src="https://cdn3.ivivu.com/2023/10/%E1%BA%A9m-th%E1%BB%B1c-%C4%91%C3%A0-n%E1%BA%B5ng-ivivu-2.jpg" alt="mì Quảng">
- <strong>Gỏi cá Nam Ô</strong>: Gỏi cá Nam Ô là một trong những món ăn đặc sản mà bạn phải thử khi đến Đà Nẵng. Món ăn này có nguồn gốc từ làng chài Nam Ô, dưới chân đèo Hải Vân thuộc quận Liên Chiểu, thành phố Đà Nẵng. Du khách có thể đi theo QL 1A hướng Nam – Bắc đến khu vực làng chài Nam Ô, dừng chân thưởng thức món gỏi cá hấp dẫn này. Gỏi cá Nam Ô có hai loại: gỏi ướt và gỏi khô. Gỏi khô thường dùng kèm bánh tráng cuốn tròn, bên trong là gỏi cá, các loại rau giá tươi, chấm với nước chấm sền sệt, chua ngọt và bùi bùi. Gỏi cá ướt với thịt cá tươi, ngọt thấm gia vị trong nước dùng cay đậm đà, được pha chế từ nước mắm thơm ngon của làng chài Nam Ô.<br>
<img src="https://cdn3.ivivu.com/2022/08/goi-ca-nam-o-da-nang-ivivu-5.jpg" alt="gỏi cá">
- <strong>Bún chả cá</strong>: Trong hành trình khám phá ẩm thực Đà Nẵng, nếu bỏ qua món bún chả cá thì thật đáng tiếc! Món ăn này tuy xuất hiện ở nhiều vùng miền của Việt Nam, nhưng tại Đà Nẵng nó lại mang một hương vị đặc trưng, khác biệt, không nơi nào có được. Bún chả cá có hai loại: chả cá hấp và chả cá chiên (hoặc cả hai). Đặc biệt, món bún chả cá ở miền Trung ngon thường do phần nước dùng. Ăn kèm với món bún chả cá là rau xà lách, giá đỗ, mắm ruốc hoặc nước mắm ớt…<br>
<img src="https://cdn3.ivivu.com/2023/10/%E1%BA%A9m-th%E1%BB%B1c-%C4%91%C3%A0-n%E1%BA%B5ng-ivivu-3.jpg" alt="bún chả cá">
- <strong>Bánh xèo</strong>: Bánh xèo Đà Nẵng có kích thước không nhỏ quá và cũng không lớn quá, chỉ riêng một kích cỡ vừa ăn. Bánh xèo ở Đà Nẵng được làm từ bột gạo xay có thêm lòng đỏ trứng và bột nghệ, đúc trên chảo nóng. Nhân bánh được làm từ các nguyên liệu qua khâu chọn lọc kĩ như tôm tươi, thịt ba chỉ nửa nạc nửa mỡ và giá đỗ tươi… Ăn kèm bánh xèo là rau sống bao gồm: xà lách, húng quế, chuối chát, rau cải con…; và nước tương pha chế từ gan heo và đậu phụng xay tạo thành một loại nước chấm có hương vị bùi bùi, béo béo hoặc một chén nước mắm ớt tỏi pha theo kiểu truyền thống.</p>
<img src="https://cdn3.ivivu.com/2023/10/%E1%BA%A9m-th%E1%BB%B1c-%C4%91%C3%A0-n%E1%BA%B5ng-ivivu-7.jpg" alt="Ẩm thực Đà Nẵng">
<p><strong>2. Địa điểm thưởng thức</strong><br>
- <strong>Chợ Cồn, chợ Hàn</strong>: Trung tâm ẩm thực với hàng trăm món ăn đường phố giá rẻ.<br>
- <strong>Quán Bà Dưỡng</strong>: Nổi tiếng với mì Quảng và bánh tráng cuốn thịt heo, địa chỉ: 23A Pasteur, Hải Châu.<br>
- <strong>Quán Bà Mua</strong>: Bún chả cá và bánh xèo ngon tuyệt, địa chỉ: 19-21 Trần Bình Trọng.</p>
<img src="https://cdn3.ivivu.com/2023/05/be-thui-cau-mong-1-ivivu.jpg" alt="Bê thui Cầu Mống">
<p><strong>3. Trải nghiệm ban đêm</strong><br>
Buổi tối, dạo bộ bên sông Hàn, ghé các quán cà phê như Memory Lounge hoặc Cộng Cà Phê để ngắm cảnh cầu Rồng lung linh. Chợ đêm Sơn Trà là nơi lý tưởng để vừa ăn uống vừa cảm nhận không khí sôi động.</p>
<img src="https://i2.ex-cdn.com/crystalbay.com/files/content/2024/07/31/cau-rong-da-nang-1-1519.jpg" alt="Cầu Rồng Đà Nẵng ban đêm">
HTML,
            'status' => 'approved',
            'image' => '/image/amthucdanang.jpg'
        ]);

        Post::create([
            'user_id' => 3,
            'location_id' => 1,
            'title' => 'Trải nghiệm chèo kayak ở Hạ Long',
            'content' => <<<HTML
<h2>Trải nghiệm chèo kayak ở Hạ Long</h2>
<p>Vịnh Hạ Long, kỳ quan thiên nhiên thế giới tại Quảng Ninh, mang đến trải nghiệm chèo kayak độc đáo, đưa du khách gần gũi với thiên nhiên hùng vĩ. Len lỏi qua các khe đá vôi giữa làn nước xanh biếc, bạn sẽ cảm nhận sự bình yên hiếm có.</p>
<p><strong>1. Vì sao nên chèo kayak ở Hạ Long?</strong><br>
- <strong>Gần gũi thiên nhiên</strong>: Chèo kayak giúp bạn khám phá các hang động, vách đá, và làng chài trên vịnh một cách chân thực.<br>
- <strong>Cảnh quan tuyệt đẹp</strong>: Ánh nắng chiếu xuống mặt nước tạo hiệu ứng lấp lánh, đặc biệt đẹp vào buổi sáng hoặc hoàng hôn.<br>
- <strong>Hoạt động thú vị</strong>: Phù hợp cho cả người mới, với hướng dẫn viên hỗ trợ đảm bảo an toàn.</p>
<img src="https://dulichvinhhalong.net.vn/wp-content/uploads/2023/05/z4926057366981_dd090cbadb5b883057579b1347514922-1024x670.jpg" alt="Chèo kayak ở Vịnh Hạ Long">
<p><strong>3. Trèo thuyền kayak mùa nào đẹp?</strong><br>
Đến tham quan du lịch Hạ Long mà bạn bỏ qua việc chèo thuyền kayak quả thật là một thiếu sót cho chuyến đi tham quan Hạ Long tự túc của bạn. Vì sao ư? Vì bạn sẽ được lênh đênh trên mặt nước, sóng êm ả, nhìn ngắm cảnh vật thiên nhiên xung quanh hài hòa, cảm nhận được sự yên bình trong tâm hồn, và có được những phút giây thoải mái thư giãn sau những ngày làm việc căng thẳng.<br>
Vậy đi chèo thuyền kayak vào mùa nào là hợp lý nhất ở Hạ Long?<br>
Do nằm ở phía Bắc của Việt Nam nên Hạ Long mang trong mình 4 mùa thời tiết đặc trưng và có khí hậu nhiệt đới ven biển. Thời tiết thường ổn định trong khoảng từ 15 đến 25 độ C. Vì thế, để nói đi Hạ Long mùa nào là hợp lí thì thật sự khó nói, vì ở đây 4 mùa luôn chào đón bạn. Mỗi mùa đều có nét đẹp riêng của nó, mỗi mùa đều mang đến cho bạn những trải nghiệm thú vị khác nhau nên có thể nói khách du lịch có thể ghé tham quan Hạ Long vào bất kì thời điểm nào trong năm. Tuy nhiên để có thời điểm thích hợp nhất để ghé thăm vùng đất Hạ Long này là từ khoảng tháng 4 đến tháng 10 hằng năm. Đây là thời điểm Hạ Long vào mùa hè, tuy là sẽ xuất hiện mưa và bão ở một đến hai tháng. Nhưng nhìn chung đây là lúc thời tiết nắng nóng rất thích hợp cho những hoạt động thể thao trên biển. Tuy nhiên, nếu muốn chuyến đi của bạn được trọn vẹn hơn hãy xem dự báo thời tiết trước để có cho mình những tính toán cẩn thận để không bỏ lỡ cuộc vui nhé.
</p>
<img src="https://mia.vn/media/uploads/blog-du-lich/cheo-thuyen-kayak-o-ha-long-co-kho-khong-cau-tra-loi-se-co-ngay-trong-bai-viet-duoi-day-03-1641906592.jpg" alt="Chèo thuyền từ tháng 4 đến tháng 10 là hợp lí">
<p><strong>2. Gợi ý chèo kayak</strong><br>
- <strong>Địa điểm</strong>: Các khu vực như làng chài Cửa Vạn, hang Luồn, đảo Titop là lý tưởng để chèo kayak.<br>
- <strong>Thời gian</strong>: Buổi sáng sớm hoặc chiều muộn, tránh nắng nóng và tận hưởng ánh sáng đẹp.<br>
- <strong>Giá thuê</strong>: Khoảng 100.000-200.000 VND/giờ, bao gồm kayak và áo phao.</p>
<img src="https://mia.vn/media/uploads/blog-du-lich/cheo-thuyen-kayak-o-ha-long-co-kho-khong-cau-tra-loi-se-co-ngay-trong-bai-viet-duoi-day-04-1641906593.jpg" alt="Hang Luồn Hạ Long">
<p><strong>3. Lưu ý khi chèo kayak</strong><br>
- Mang áo phao và tuân thủ hướng dẫn để đảm bảo an toàn.<br>
- Tránh chèo xa khu vực được chỉ định để không lạc hướng.<br>
- Chuẩn bị quần áo thoải mái, chống thấm nước và mũ để bảo vệ khỏi nắng.</p>
<img src="https://mia.vn/media/uploads/blog-du-lich/cheo-thuyen-kayak-o-ha-long-co-kho-khong-cau-tra-loi-se-co-ngay-trong-bai-viet-duoi-day-06-1641906593.jpg" alt="Kayak tại làng chài Cửa Vạn">
HTML,
            'status' => 'approved',
            'image' => '/image/placeholder.jpg',
        ]);

        Post::create([
            'user_id' => 1,
            'location_id' => 5,
            'title' => 'Phố cổ Hội An lung linh về đêm',
            'content' => <<<HTML
<h2>Phố cổ Hội An lung linh về đêm</h2>
<p>Hội An, phố cổ di sản thế giới tại Quảng Nam, đẹp mộng mơ với những con phố lát đá và đèn lồng rực rỡ. Ban đêm, Hội An trở nên huyền ảo, hòa quyện giữa nét cổ kính và không khí hiện đại, khiến du khách không khỏi xao lòng.</p>
<p><strong>1. Trải nghiệm đêm Hội An</strong><br>
- <strong>Dạo phố đèn lồng</strong>: Các con phố như Nguyễn Thị Minh Khai, Trần Phú lung linh với hàng trăm đèn lồng đủ màu sắc.<br>
- <strong>Thả đèn hoa đăng</strong>: ễ hội hoa đăng Hội An được tổ chức trên dòng sông Hoài, nằm hiền hoà giữa lòng phố cổ. Đây cũng là con sông gắn với nhiều hoạt động sinh hoạt, văn hoá của người dân nơi đây. Sông Hoài vốn êm đềm, bình lặng nhưng vào những đêm trăng rằm, nơi đây sẽ trở nên náo nhiệt, thơ mộng hơn bởi hàng trăm lượt khách đổ về gửi lời nguyện ước bên những chiếc đèn hoa đăng rực rỡ.<br>
Thả đèn hoa đăng Hội An là một nét đẹp trong văn hoá tâm linh của người dân phố cổ nói riêng và của người Việt Nam nói chung. Hoạt động thả đèn được xem là để đem lại sức khoẻ và tài lộc cho mọi người. Khi những chiếc đèn được thả xuống nghĩa là thắp lên hy vọng về một tương lai nhiều may mắn, bình an cho gia đình, người thân. Đây là lễ hội ở Hội An được nhiều du khách mong chờ nhất. <br>
- <strong>Nghe nhạc ven sông</strong>: Các quán cà phê như Faifo Coffee hay Reaching Out Tea House mang đến không gian thư giãn với nhạc du dương.</p>
<img src="https://statics.vinpearl.com/den-long-hoi-an-1_1628212083.png" alt="Đèn lồng Hội An ban đêm">
<p><strong>2. Địa điểm tham quan nổi bật</strong><br>
- <strong>Chùa Cầu</strong>: Chùa Cầu là viên ngọc giữa lòng Hội An. Cầu xây dựng vào cuối thế kỷ 16 và được gọi là cầu Nhật Bản. Ở giữa cầu có một ngôi miếu nhỏ thờ Huyền Thiên Đại Đế. Cầu có mái che khá độc đáo cùng các kết cấu, họa tiết trang trí. Thể hiện sự kết hợp hài hòa giữa các phong cách kiến trúc Việt, Hoa, Nhật, và cả phương Tây.<br>
- <strong>Chợ đêm Hội An</strong>: Nơi thưởng thức ẩm thực như cao lầu, bánh mì Phượng, và mua quà lưu niệm.<br>
- <strong>Làng gốm Thanh Hà</strong>: Vào thế kỷ 16, 17, Thanh Hà là một ngôi làng rất thịnh đạt, nổi tiếng về các mặt hàng gốm, đất nung. Được trao đổi, bán buôn khắp các tỉnh miền Trung Việt Nam. Nghề gốm của làng có nguồn gốc xuất xứ từ Thanh Hóa. Sau khi tiếp thu được một số vốn liếng kỹ thuật thì đã hình thành làng gốm như ngày nay. Sản phẩm chủ yếu là các đồ dùng phục vụ đời sống sinh hoạt hàng ngày như chén, bát, chum, vại, bình hoa, chậu cảnh, hình thù các con giống… mang nhiều kiểu dáng, màu sắc rất phong phú và đặc biệt nhẹ hơn so với các sản phẩm cùng loại của địa phương khác.</p>
<img src="https://cdn3.ivivu.com/2023/10/du-lich-hoi-an-ivivu-img31.jpg" alt="làng gốm Thanh Hà">
<p><strong>3. Gợi ý lưu trú</strong><br>
- <strong>Khách sạn</strong>: Anantara Hoi An Resort, giá từ 2.500.000 VND/phòng/đêm, phong cách cổ điển.<br>
- <strong>Homestay</strong>: Under The Coconut Tree, giá từ 400.000 VND/phòng/đêm, gần gũi thiên nhiên.<br>
- <strong>Resort</strong>: Vinpearl Resort & Golf Nam Hoi An, giá từ 2.000.000 VND/phòng/đêm, tiện ích cao cấp.</p>
<img src="https://statics.vinpearl.com/kinh-nghiem-du-lich-hoi-an-10_1661245530.jpg" alt="Sông Hoài ban đêm">
HTML,
            'status' => 'approved',
            'image' => '/image/hoian.avif',
        ]);

        Post::create([
            'user_id' => 2,
            'location_id' => 5,
            'title' => 'Phố cổ Hội An lung linh về đêm',
            'content' => <<<HTML
<h2>Phố cổ Hội An lung linh về đêm</h2>
<p>Hội An, phố cổ di sản thế giới tại Quảng Nam, đẹp mộng mơ với những con phố lát đá và đèn lồng rực rỡ. Ban đêm, Hội An trở nên huyền ảo, hòa quyện giữa nét cổ kính và không khí hiện đại, khiến du khách không khỏi xao lòng.</p>
<p><strong>1. Trải nghiệm đêm Hội An</strong><br>
- <strong>Dạo phố đèn lồng</strong>: Các con phố như Nguyễn Thị Minh Khai, Trần Phú lung linh với hàng trăm đèn lồng đủ màu sắc.<br>
- <strong>Thả đèn hoa đăng</strong>: ễ hội hoa đăng Hội An được tổ chức trên dòng sông Hoài, nằm hiền hoà giữa lòng phố cổ. Đây cũng là con sông gắn với nhiều hoạt động sinh hoạt, văn hoá của người dân nơi đây. Sông Hoài vốn êm đềm, bình lặng nhưng vào những đêm trăng rằm, nơi đây sẽ trở nên náo nhiệt, thơ mộng hơn bởi hàng trăm lượt khách đổ về gửi lời nguyện ước bên những chiếc đèn hoa đăng rực rỡ.<br>
Thả đèn hoa đăng Hội An là một nét đẹp trong văn hoá tâm linh của người dân phố cổ nói riêng và của người Việt Nam nói chung. Hoạt động thả đèn được xem là để đem lại sức khoẻ và tài lộc cho mọi người. Khi những chiếc đèn được thả xuống nghĩa là thắp lên hy vọng về một tương lai nhiều may mắn, bình an cho gia đình, người thân. Đây là lễ hội ở Hội An được nhiều du khách mong chờ nhất. <br>
- <strong>Nghe nhạc ven sông</strong>: Các quán cà phê như Faifo Coffee hay Reaching Out Tea House mang đến không gian thư giãn với nhạc du dương.</p>
<img src="https://statics.vinpearl.com/den-long-hoi-an-1_1628212083.png" alt="Đèn lồng Hội An ban đêm">
<p><strong>2. Địa điểm tham quan nổi bật</strong><br>
- <strong>Chùa Cầu</strong>: Chùa Cầu là viên ngọc giữa lòng Hội An. Cầu xây dựng vào cuối thế kỷ 16 và được gọi là cầu Nhật Bản. Ở giữa cầu có một ngôi miếu nhỏ thờ Huyền Thiên Đại Đế. Cầu có mái che khá độc đáo cùng các kết cấu, họa tiết trang trí. Thể hiện sự kết hợp hài hòa giữa các phong cách kiến trúc Việt, Hoa, Nhật, và cả phương Tây.<br>
- <strong>Chợ đêm Hội An</strong>: Nơi thưởng thức ẩm thực như cao lầu, bánh mì Phượng, và mua quà lưu niệm.<br>
- <strong>Làng gốm Thanh Hà</strong>: Vào thế kỷ 16, 17, Thanh Hà là một ngôi làng rất thịnh đạt, nổi tiếng về các mặt hàng gốm, đất nung. Được trao đổi, bán buôn khắp các tỉnh miền Trung Việt Nam. Nghề gốm của làng có nguồn gốc xuất xứ từ Thanh Hóa. Sau khi tiếp thu được một số vốn liếng kỹ thuật thì đã hình thành làng gốm như ngày nay. Sản phẩm chủ yếu là các đồ dùng phục vụ đời sống sinh hoạt hàng ngày như chén, bát, chum, vại, bình hoa, chậu cảnh, hình thù các con giống… mang nhiều kiểu dáng, màu sắc rất phong phú và đặc biệt nhẹ hơn so với các sản phẩm cùng loại của địa phương khác.</p>
<img src="https://cdn3.ivivu.com/2023/10/du-lich-hoi-an-ivivu-img31.jpg" alt="làng gốm Thanh Hà">
<p><strong>3. Gợi ý lưu trú</strong><br>
- <strong>Khách sạn</strong>: Anantara Hoi An Resort, giá từ 2.500.000 VND/phòng/đêm, phong cách cổ điển.<br>
- <strong>Homestay</strong>: Under The Coconut Tree, giá từ 400.000 VND/phòng/đêm, gần gũi thiên nhiên.<br>
- <strong>Resort</strong>: Vinpearl Resort & Golf Nam Hoi An, giá từ 2.000.000 VND/phòng/đêm, tiện ích cao cấp.</p>
<img src="https://statics.vinpearl.com/kinh-nghiem-du-lich-hoi-an-10_1661245530.jpg" alt="Sông Hoài ban đêm">
HTML,
            'status' => 'pending',
            'image' => '/image/hoian.avif',
        ]);
    }
}
