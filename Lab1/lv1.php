<!DOCTYPE html>
<html>
        <head>
        </head>

        <body>
        <?php
                include('./simple_html_dom.php');
                interface iRadovi {
                        public function create($data);
                        public function save();
                        public function read();
                }

                class DiplomskiRad implements iRadovi {
                        private $naziv_rada;
                        private $tekst_rada;
                        private $link_rada;
                        private $oib_tvrtke;
                    
                        function __construct($data) {
                                $this->naziv_rada = $data['naziv_rada'];
                                $this->tekst_rada = $data['tekst_rada'];
                                $this->link_rada = $data['link_rada'];
                                $this->oib_tvrtke = $data['oib_tvrtke'];
                        }

                        function create($data) {
                                self::__construct($data);
                        }

                        function save() {
                                if (empty($this->naziv_rada) || empty($this->tekst_rada) || empty($this->link_rada)) {
                                    return;
                                }
                            
                                $conn = new mysqli("localhost", "root", "", "radovi");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                            
                                $stmt = $conn->prepare("INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param('ssss', $this->naziv_rada, $this->tekst_rada, $this->link_rada, $this->oib_tvrtke);
                            
                                if (!$stmt->execute()) {
                                    echo "Greška pri spremanju: " . $stmt->error;
                                }
                            
                                $stmt->close();
                                $conn->close();
                            }
                            

                        function read() {
                                $conn = new mysqli("localhost", "root", "", "radovi");
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                                
                                $sql = "SELECT * FROM `diplomski_radovi` 
                                        WHERE naziv_rada != '' AND tekst_rada != ''";
                            
                                $output = $conn->query($sql);
                            
                                if ($output->num_rows > 0) {
                                    while($item = $output->fetch_assoc()) {
                                        echo "<br>ID: " . $item["id"] .
                                             "<br>OIB tvrtke: " . $item["oib_tvrtke"] .
                                             "<br>Naziv rada: " . $item["naziv_rada"] .
                                             "<br>Link rada: " . $item["link_rada"] .
                                             "<br>Tekst rada: " . $item["tekst_rada"] . "<hr>";
                                    }
                                } else {
                                    echo "Nema pronađenih radova s potpunim podacima.";
                                }
                            
                                $conn->close();
                            }                            
                }


                $url = 'http://stup.ferit.hr/index.php/zavrsni-radovi/page/2';
                $read = file_get_html($url);

                foreach($read->find('article') as $article) {
                $img = $article->find('ul.slides img', 0);
                $link = $article->find('h2.entry-title a', 0);
                if (!$img || !$link) continue;

                $html = file_get_html($link->href);
                if (!$html) continue;

                $text = $html->find('.post-content', 0);
                if (!$text) continue;

                $rad = [
                        'naziv_rada' => trim($link->plaintext),
                        'tekst_rada' => trim($text->plaintext),
                        'link_rada' => $link->href,
                        'oib_tvrtke' => preg_replace('/[^0-9]/', '', $img->src)
                ];

                $newRad = new DiplomskiRad($rad);
                $newRad->save();  
                }

                $newRad->read();

                    
        ?>
        </body>
</html>

