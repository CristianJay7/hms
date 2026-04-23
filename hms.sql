-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2026 at 11:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hms`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'published',
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `excerpt`, `content`, `published_date`, `status`, `image`, `created_at`) VALUES
(1, 'Diabetes', 'Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.', 'Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.\r\n\r\nLearn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.Learn about diabetes prevention and management for a healthier lifestyle.', '2026-03-19', 'published', '', '2026-03-18 18:28:35'),
(2, 'COVID-19 Vaccine', 'Stay informed about the latest COVID-19 vaccine updates and guidelines.', '', '2026-03-19', 'published', '', '2026-03-18 18:28:35'),
(3, 'Prevent Epidemic', 'Simple steps to protect yourself and your community from epidemics.', '', '2026-03-19', 'published', '', '2026-03-18 18:28:35'),
(6, 'fire drill', 'sample sample sample sample sammple sample', 'samsample sample sample sample sammple samplesample sample sample sample sammple samplesample sample sample sample sammple samplesample sample sample sample sammple samplesample sample sample sample sammple sample', '2026-04-20', 'published', '', '2026-04-20 12:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `blog_photos`
--

CREATE TABLE `blog_photos` (
  `id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_photos`
--

INSERT INTO `blog_photos` (`id`, `blog_id`, `photo`, `sort_order`, `created_at`) VALUES
(12, 1, '/hms/admin/images/blogs/gallery_69db42925e8cd.jpg', 0, '2026-04-12 06:58:26'),
(13, 1, '/hms/admin/images/blogs/gallery_69db429271274.jpg', 0, '2026-04-12 06:58:26');

-- --------------------------------------------------------

--
-- Table structure for table `career_jobs`
--

CREATE TABLE `career_jobs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `department` varchar(150) DEFAULT NULL,
  `type` enum('Full-time','Part-time','Contract','Internship') DEFAULT 'Full-time',
  `location` varchar(150) DEFAULT 'Zamboanga City',
  `description` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `posted_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `career_jobs`
--

INSERT INTO `career_jobs` (`id`, `title`, `department`, `type`, `location`, `description`, `requirements`, `status`, `posted_date`, `created_at`) VALUES
(1, 'Registered Nurse', 'Nursing Department', 'Internship', 'Zamboanga City', 'We are looking for a compassionate and dedicated Registered Nurse to join our nursing team. The successful candidate will provide high-quality patient care, collaborate with physicians and other healthcare professionals, and ensure patient safety and comfort at all times.', '- Must be a licensed Registered Nurse\r\n- At least 1 year of clinical experience\r\n- Strong communication and interpersonal skills\r\n- Willing to work on shifting schedules', 'open', '2026-03-28', '2026-03-27 20:21:59'),
(2, 'Medical Technologist', 'Laboratory', 'Full-time', 'Zamboanga City', 'We are seeking a skilled Medical Technologist to perform laboratory tests and procedures that support the diagnosis and treatment of patients. The role involves operating laboratory equipment, analyzing specimens, and reporting results accurately.', '- Licensed Medical Technologist\r\n- Proficiency in laboratory procedures\r\n- Attention to detail and accuracy\r\n- Experience with laboratory information systems is an advantage', 'closed', '2026-03-28', '2026-03-27 20:21:59'),
(3, 'Registered Pharmacist', 'Pharmacy Department', 'Full-time', 'Zamboanga City', 'Responsible for preparing and dispensing medications, assisting customers/patients, maintaining inventory, and ensuring compliance with health and safety regulations. Works under the supervision of a licensed pharmacist', '-College level (Assistant) or Licensed Pharmacist\r\n-With experience in pharmacy or retail is an advantage\r\n-Knowledge in medications and basic medical terms\r\n-Ability to work under pressure and shifting schedules', 'open', '2026-03-31', '2026-03-31 03:51:47');

-- --------------------------------------------------------

--
-- Table structure for table `certifications`
--

CREATE TABLE `certifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `file` varchar(500) NOT NULL,
  `file_type` enum('pdf','docx') NOT NULL DEFAULT 'pdf',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `clinic_hours` varchar(150) NOT NULL,
  `availability` varchar(200) NOT NULL,
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialization`, `clinic_hours`, `availability`, `image`, `created_at`) VALUES
(1, 'Dr. James Carter', 'Cardiologist', 'Mon–Fri, 8:00 AM – 5:00 PM', 'Walk-ins welcome, 00656950454, room 69', 'admin/images/doctor_1775401810_186.jpg', '2026-03-08 13:08:45'),
(2, 'Dr. Sarah Lin', 'Neurologist', 'Tue–Sat, 9:00 AM – 6:00 PM', 'Appointments required\r\nClinic 123\r\n0998797878', 'images/doctor-2.jpg', '2026-03-08 13:08:45'),
(3, 'Dr. Maria Santos', 'Pediatrician', 'Mon–Thu, 8:00 AM – 4:00 PM', 'walk-ins, 09978676576, clinic no. 232', 'images/doctor-3.jpg', '2026-03-08 13:08:45');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `services_offered` text DEFAULT NULL,
  `schedules` text DEFAULT NULL,
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facilities`
--

INSERT INTO `facilities` (`id`, `name`, `description`, `services_offered`, `schedules`, `image`, `created_at`) VALUES
(1, 'Operating Theaters', 'Our state-of-the-art operating theaters are equipped with the latest surgical technology and staffed by highly skilled surgeons, anesthesiologists, and surgical nurses. We perform a wide range of elective and emergency surgical procedures in a sterile, safe, and controlled environment.\r\n\r\nEach theater is designed to minimize infection risks while maximizing precision and efficiency, ensuring the best possible outcomes for every patient.', 'sample', 'sample', '/hms/admin/images/facilities/facility_1775151914_467.jpg', '2026-03-08 22:29:38'),
(2, 'Advanced Imaging Center', 'Our Advanced Imaging Center houses cutting-edge diagnostic equipment including MRI, CT scan, X-ray, and ultrasound machines. Our team of radiologists and imaging technologists deliver accurate and timely results to support effective diagnosis and treatment planning.\r\n\r\nWe are committed to patient comfort and safety throughout every imaging procedure, using the lowest possible radiation doses without compromising image quality.', NULL, NULL, '/hms/admin/images/facilities/facility_1775291881_644.jpg', '2026-03-08 22:29:38'),
(3, 'ICU and High-Dependency Units (HDU)', 'Our Intensive Care Unit and High-Dependency Units provide round-the-clock monitoring and care for critically ill patients. Staffed by intensivists, specialists, and highly trained critical care nurses, our ICU and HDU are equipped with advanced life support and monitoring systems.\r\n\r\nWe provide individualized care plans and keep families informed and involved in every step of their loved one\'s recovery.', NULL, NULL, '/hms/admin/images/facilities/facility_1775291936_542.jpg', '2026-03-08 22:29:38'),
(4, 'Laboratory and Pathology Facility', 'Our fully accredited laboratory and pathology facility offers a comprehensive range of diagnostic tests including hematology, clinical chemistry, microbiology, and histopathology. Results are processed with precision and delivered promptly to support accurate clinical decisions.\r\n\r\nOur licensed medical technologists uphold the highest standards of quality control to ensure reliable and consistent results every time.', NULL, NULL, '/hms/admin/images/facilities/facility_1775291942_228.jpg', '2026-03-08 22:29:38'),
(5, 'Blood Bank', 'Our Blood Bank operates 24 hours a day to ensure a safe and adequate supply of blood and blood components for patients in need. All donations are carefully screened and processed following strict national and international standards.\r\n\r\nWe work closely with clinical teams to ensure timely availability of blood products for surgical, emergency, and transfusion-dependent patients.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:29:38'),
(6, 'Rehabilitation Center and Physical Therapy', 'Our Rehabilitation Center provides comprehensive physical, occupational, and speech therapy services for patients recovering from surgery, injury, stroke, and other medical conditions. Our licensed therapists create personalized rehabilitation programs aimed at restoring function and improving quality of life.\n\nWe believe that healing goes beyond the hospital stay, and our team is dedicated to supporting patients throughout their entire recovery journey.', NULL, NULL, 'admin/images/facilities/rehab.jpg', '2026-03-08 22:29:38'),
(7, 'Dialysis Unit', 'Our modern Dialysis Unit offers hemodialysis services for patients with chronic kidney disease and acute renal failure. Equipped with the latest dialysis machines and monitored by nephrologists and trained dialysis nurses, we ensure safe and comfortable treatment sessions.\r\n\r\nWe offer flexible scheduling to accommodate the needs of our regular dialysis patients, with a clean and calming environment designed for their comfort.', NULL, NULL, '/hms/admin/images/facilities/facility_1775291973_938.jpg', '2026-03-08 22:29:38'),
(8, 'Ambulance and Emergency Transport', 'Our ambulance fleet is available 24/7 for emergency response and inter-facility patient transfers. Each vehicle is fully equipped with medical-grade life support equipment and staffed by trained emergency medical technicians.\n\nWe are committed to rapid response times and delivering continuous medical care from the point of emergency all the way to the hospital.', NULL, NULL, 'admin/images/facilities/ambulance.jpg', '2026-03-08 22:29:38'),
(9, 'Patient Wards', 'Our patient wards are designed to provide a comfortable, safe, and healing environment for admitted patients. Available in private, semi-private, and ward configurations, our rooms are well-maintained and equipped with modern amenities.\n\nOur nursing staff provides attentive, compassionate care around the clock, ensuring that every patient feels supported and comfortable throughout their stay.', NULL, NULL, 'admin/images/facilities/wards.jpg', '2026-03-08 22:29:38');

-- --------------------------------------------------------

--
-- Table structure for table `facility_photos`
--

CREATE TABLE `facility_photos` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility_photos`
--

INSERT INTO `facility_photos` (`id`, `facility_id`, `photo`, `sort_order`, `created_at`) VALUES
(4, 1, '/hms/admin/images/facilities/fac_gallery_69d9184060d04.jpg', 0, '2026-04-10 15:33:20'),
(5, 1, '/hms/admin/images/facilities/fac_gallery_69d918406dc0c.jpg', 0, '2026-04-10 15:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `question` varchar(300) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `sort_order`, `status`, `created_at`) VALUES
(1, 'What are your hospital visiting hours?', 'Visiting hours are from 10:00 AM to 12:00 PM and 5:00 PM to 8:00 PM daily. ICU and special wards may have different visiting policies. Please coordinate with the nursing station for specific guidelines.', 1, 'published', '2026-03-31 01:59:00'),
(2, 'Do you accept HMO and insurance cards?', 'Yes, we accept a wide range of HMO and insurance providers. Please bring your valid HMO card and any required authorization letters upon admission. You may check our HMO & Insurance Partners section for the full list of accredited providers.', 2, 'published', '2026-03-31 01:59:00'),
(3, 'How do I book an appointment with a doctor?', 'You may call our outpatient department directly, visit the hospital in person, or contact us through our official communication channels. Our staff will assist you in scheduling a consultation at your preferred date and time.', 3, 'published', '2026-03-31 01:59:00'),
(4, 'Is the emergency room open 24/7?', 'Yes, our Emergency Room operates 24 hours a day, 7 days a week including holidays. Our emergency physicians and nursing staff are always ready to attend to critical and urgent medical cases.', 4, 'published', '2026-03-31 01:59:00'),
(5, 'Where is the hospital located?', 'Zamboanga Doctors\' Hospital is located in Zamboanga City, Philippines. Please refer to the contact page for our complete address and a map to our location.', 5, 'published', '2026-03-31 01:59:00'),
(6, 'What payment methods do you accept?', 'We accept cash, major credit and debit cards, and approved HMO and insurance plans. For questions about billing and payment options, please contact our billing department.', 6, 'published', '2026-03-31 01:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `title`, `image`, `created_at`) VALUES
(1, 'basketball', 'admin/images/gallery/gallery_1773078202_625.jpg', '2026-03-09 17:43:22'),
(2, 'pres', 'admin/images/gallery/gallery_1773078220_371.jpg', '2026-03-09 17:43:40'),
(3, 'fountain', 'admin/images/gallery/gallery_1773078515_750.jpg', '2026-03-09 17:48:35'),
(4, 'front', 'admin/images/gallery/gallery_1773078527_433.jpg', '2026-03-09 17:48:47'),
(5, 'entrance', 'admin/images/gallery/gallery_1773078543_649.jpg', '2026-03-09 17:49:03'),
(6, 'zdh', 'admin/images/gallery/gallery_1773078561_250.jpg', '2026-03-09 17:49:21'),
(9, 'founders', 'admin/images/gallery/gallery_1774506540_403.jpg', '2026-03-26 06:29:00'),
(10, 'ambulance', 'admin/images/gallery/gallery_1774510496_735.jpg', '2026-03-26 07:34:56'),
(11, 'sample', 'admin/images/gallery/gallery_1774510615_695.jpg', '2026-03-26 07:36:55'),
(12, 'covid', 'admin/images/gallery/gallery_1774510682_356.jpg', '2026-03-26 07:38:02'),
(13, 'samplee2', 'admin/images/gallery/gallery_1774510710_511.jpg', '2026-03-26 07:38:30'),
(14, 'sample3', 'admin/images/gallery/gallery_1774510744_210.jpg', '2026-03-26 07:39:04'),
(15, 'sample4', 'admin/images/gallery/gallery_1774510803_170.jpg', '2026-03-26 07:40:03'),
(16, 'sample5', 'admin/images/gallery/gallery_1774510846_462.jpg', '2026-03-26 07:40:46'),
(17, 'sam4', 'admin/images/default.jpg', '2026-03-26 07:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `hmos`
--

CREATE TABLE `hmos` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hmos`
--

INSERT INTO `hmos` (`id`, `name`, `logo`, `created_at`) VALUES
(1, 'Fortune Life', 'admin/images/hmos/hmo_1775976663_373.png', '2026-03-08 21:31:25'),
(2, 'Generali Philippines', 'admin/images/hmos/hmo_1774507756_392.jpg', '2026-03-08 21:31:25'),
(3, 'Getwell Health', 'admin/images/hmos/hmo_1774508582_184.png', '2026-03-08 21:31:25'),
(4, 'HMI', 'admin/images/hmos/hmo_1774508635_351.png', '2026-03-08 21:31:25'),
(5, 'HPPI', 'admin/images/hmos/hmo_1774508669_948.png', '2026-03-08 21:31:25'),
(6, 'IMS Well Care', 'admin/images/hmos/hmo_1774508716_825.jpg', '2026-03-08 21:31:25'),
(7, 'InLife', 'admin/images/hmos/hmo_1774508764_339.png', '2026-03-08 21:31:25'),
(8, 'Intellicare', 'admin/images/hmos/hmo_1774508130_348.png', '2026-03-08 21:31:25'),
(9, 'International SOS', 'admin/images/hmos/hmo_1774508829_399.png', '2026-03-08 21:31:25'),
(10, 'Kaiser International', 'admin/images/hmos/hmo_1774508870_975.png', '2026-03-08 21:31:25'),
(11, 'Lacson & Lacson', 'admin/images/hmos/hmo_1774508913_195.png', '2026-03-08 21:31:25'),
(12, 'Life & Health', 'admin/images/hmos/hmo_1774508960_991.jpg', '2026-03-08 21:31:25'),
(13, 'Maxicare', 'admin/images/hmos/hmo_1774507886_181.jpg', '2026-03-08 21:31:25'),
(14, 'MedAsia', 'admin/images/hmos/hmo_1774509008_551.png', '2026-03-08 21:31:25'),
(15, 'Medicard', 'admin/images/hmos/hmo_1774508186_705.png', '2026-03-08 21:31:25'),
(16, 'Medilink', 'admin/images/hmos/hmo_1774509049_894.png', '2026-03-08 21:31:25'),
(17, 'Medocare', 'admin/images/hmos/hmo_1774509121_234.png', '2026-03-08 21:31:25'),
(18, 'MedPharm', 'admin/images/hmos/hmo_1774509157_412.png', '2026-03-08 21:31:25'),
(19, 'Pacific Cross', 'admin/images/hmos/hmo_1774509219_193.png', '2026-03-08 21:31:25'),
(20, 'PhilCare', 'admin/images/hmos/hmo_1774507978_165.png', '2026-03-08 21:31:25'),
(21, 'Philippine British', 'admin/images/hmos/hmo_1774509253_252.png', '2026-03-08 21:31:25'),
(22, 'StayWell', 'admin/images/hmos/hmo_1774509283_757.png', '2026-03-08 21:31:25'),
(23, 'Sun Life Grepa', 'admin/images/hmos/hmo_1774509327_972.png', '2026-03-08 21:31:25'),
(24, 'ValuCare', 'admin/images/hmos/hmo_1774508245_478.png', '2026-03-08 21:31:25'),
(25, 'WellCare', 'admin/images/hmos/hmo_1774508304_740.png', '2026-03-08 21:31:25');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(500) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `patient_rights`
--

CREATE TABLE `patient_rights` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_rights`
--

INSERT INTO `patient_rights` (`id`, `title`, `description`, `sort_order`, `created_at`) VALUES
(1, 'Right to Quality Care', 'You have the right to receive considerate, respectful, and quality medical care regardless of your background, religion, or financial status.', 1, '2026-04-09 18:18:46'),
(2, 'Right to Information', 'You have the right to be fully informed about your diagnosis, treatment options, risks, and prognosis in a language you understand.', 2, '2026-04-09 18:18:46'),
(3, 'Right to Privacy', 'Your medical records and personal information are kept strictly confidential in accordance with the Data Privacy Act of the Philippines.', 3, '2026-04-09 18:18:46'),
(4, 'Right to Consent', 'You have the right to accept or refuse any treatment after being fully informed of the consequences of your decision.', 4, '2026-04-09 18:18:46'),
(5, 'Right to Grievance', 'You have the right to file a complaint or grievance without fear of retaliation. Contact our Patient Relations Office for assistance.', 5, '2026-04-09 18:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `privacy_sections`
--

CREATE TABLE `privacy_sections` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT 'fa-solid fa-circle-info',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `privacy_sections`
--

INSERT INTO `privacy_sections` (`id`, `icon`, `title`, `content`, `sort_order`, `created_at`) VALUES
(1, 'fa-solid fa-circle-info', 'Overview', 'Zamboanga Doctors\' Hospital, Inc. is committed to protecting the privacy and confidentiality of your personal and medical information. This notice describes how we collect, use, and safeguard your information.', 1, '2026-04-09 18:18:46'),
(2, 'fa-solid fa-database', 'Information We Collect', 'We collect personal information including your name, contact details, date of birth, and medical history necessary to provide healthcare services.', 2, '2026-04-09 18:18:46'),
(3, 'fa-solid fa-stethoscope', 'How We Use Your Information', 'Your information is used solely for treatment, payment, and healthcare operations. We do not sell or share your personal information with third parties without your consent, except as required by law.', 3, '2026-04-09 18:18:46'),
(4, 'fa-solid fa-user-check', 'Your Rights', 'You have the right to access, correct, and request deletion of your personal information. Contact our Data Privacy Officer for any concerns.', 4, '2026-04-09 18:18:46'),
(5, 'fa-solid fa-envelope', 'Contact Us', 'For privacy-related concerns, contact our Data Privacy Officer at the hospital\'s main office or via email.', 5, '2026-04-09 18:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(150) NOT NULL,
  `review_text` text NOT NULL,
  `rating` tinyint(1) DEFAULT 5,
  `photo` varchar(255) DEFAULT '',
  `review_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `patient_name`, `review_text`, `rating`, `photo`, `review_date`, `created_at`) VALUES
(1, 'Maria Santos', 'The doctors and nurses were very attentive and caring. I felt safe and well-taken care of throughout my stay.', 5, '', '2024-11-15', '2026-03-12 19:58:30'),
(2, 'Juan dela Cruz', 'Excellent service and modern facilities. The staff went above and beyond to make me comfortable during my recovery.', 5, '', '2024-10-22', '2026-03-12 19:58:30'),
(3, 'Ana Reyes', 'Very professional and compassionate team. I highly recommend Zamboanga Doctors Hospital to anyone seeking quality healthcare.', 4, '', '2024-12-05', '2026-03-12 19:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `roomrates`
--

CREATE TABLE `roomrates` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT 0.00,
  `capacity` varchar(100) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roomrates`
--

INSERT INTO `roomrates` (`id`, `name`, `description`, `price_per_night`, `capacity`, `amenities`, `image`, `created_at`) VALUES
(1, 'Ward', 'Shared accommodation for patients requiring standard medical care.', 1200.00, '1-3 patients', 'Shared bathroom, Nurse call button, Basic bedding, Electric fan', 'admin/images/roomrates/room_69d2e511488c4.jpg', '2026-03-12 14:06:49'),
(2, 'OB Ward', 'Dedicated ward for obstetric patients and new mothers.', 2000.00, '2 patients', 'Shared bathroom, Nurse call button, Baby crib, Aircon', '', '2026-03-12 14:06:49'),
(3, 'Regular Private', 'Private room with essential amenities for comfortable recovery.', 2000.00, '1 patient', 'Private bathroom, Air conditioning, Telephone', '', '2026-03-12 14:06:49'),
(4, 'Big Private', 'Spacious private room with enhanced comfort and amenities.', 2200.00, '1 patient', 'Private bathroom, Air conditioning, Refrigerator, Telephone', '', '2026-03-12 14:06:49'),
(5, 'Large Private', 'Extra-large private room ideal for extended stays.', 2700.00, '1 patient', 'Private bathroom, TV, Air conditioning, Refrigerator, Telephone', '', '2026-03-12 14:06:49'),
(6, 'Deluxe', 'Deluxe room with premium furnishings and enhanced privacy.', 3500.00, '1 patient', 'Private bathroom, TV, Air conditioning, Refrigerator, Hot and Cold Facility, Dining Area, Telephone', '', '2026-03-12 14:06:49'),
(7, 'Suite', 'Luxurious suite with a separate living area for maximum comfort.', 4000.00, '1 patient', 'Private bathroom, TV, Air conditioning, Telephone, Refrigerator, Dining area, Kitchenette, Hot and Cold Facility', '', '2026-03-12 14:06:49'),
(8, 'Presidential Suite', 'Top-tier suite offering the highest level of comfort and exclusivity.', 6000.00, '1 patient', 'Private bathroom, LED TV, Air conditioning, Refrigerator, Telephone, Ante-room with TV, Dining Area, Kitchenette, Microwave oven, Hot and Cold Facility', '', '2026-03-12 14:06:49'),
(9, 'ICU', 'Intensive Care Unit for patients requiring continuous monitoring.', 6000.00, 'Individual', 'Life support equipment, 24/7 monitoring, Specialized nursing care', 'admin/images/roomrates/room_69d2e439be2c9.jpg', '2026-03-12 14:06:49'),
(10, 'NICU', 'Neonatal Intensive Care Unit for premature or critically ill newborns.', 2200.00, 'Individual', 'Incubators, Neonatal monitoring, Specialized neonatal nursing', '', '2026-03-12 14:06:49'),
(11, 'Semi Private', 'Shared accommodation for patients requiring standard medical care.', 1500.00, '2 patients', 'Wall fan, Shared Bathroom, Basic bedding', '', '2026-03-12 15:09:08');

-- --------------------------------------------------------

--
-- Table structure for table `room_photos`
--

CREATE TABLE `room_photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `room_id` int(10) UNSIGNED NOT NULL,
  `photo` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room_photos`
--

INSERT INTO `room_photos` (`id`, `room_id`, `photo`, `created_at`) VALUES
(1, 1, '/hms/admin/images/roomrates/room_gallery_69e156b844309.jpg', '2026-04-16 21:38:00'),
(2, 1, '/hms/admin/images/roomrates/room_gallery_69e15a6ef1ddb.jpg', '2026-04-16 21:53:50'),
(3, 1, '/hms/admin/images/roomrates/room_gallery_69e15a815a446.jpg', '2026-04-16 21:54:09');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `icon` varchar(100) DEFAULT 'fa-solid fa-stethoscope',
  `description` text DEFAULT NULL,
  `services_offered` text DEFAULT NULL,
  `schedules` text DEFAULT NULL,
  `image` varchar(255) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `icon`, `description`, `services_offered`, `schedules`, `image`, `created_at`) VALUES
(1, 'Ambulance', 'fa-solid fa-truck-medical', 'Our 24/7 ambulance service ensures rapid medical response for emergencies across Zamboanga City and nearby areas. Equipped with life-saving equipment and staffed by trained emergency medical technicians, our ambulances are always ready to provide immediate care during transport.\r\nWhether it is a critical emergency or a scheduled hospital transfer, our team is committed to delivering safe, fast, and compassionate service to every patient.\r\nOur 24/7 ambulance service ensures rapid medical response for emergencies across Zamboanga City and nearby areas. Equipped with life-saving equipment and staffed by trained emergency medical technicians, our ambulances are always ready to provide immediate care during transport.\r\nWhether it is a critical emergency or a scheduled hospital transfer, our team is committed to delivering safe, fast, and compassionate service to every patient.\r\nOur 24/7 ambulance service ensures rapid medical response for emergencies across Zamboanga City and nearby areas. Equipped with life-saving equipment and staffed by trained emergency medical technicians, our ambulances are always ready to provide immediate care during transport.\r\nWhether it is a critical emergency or a scheduled hospital transfer, our team is committed to delivering safe, fast, and compassionate service to every patient.\r\nOur 24/7 ambulance service ensures rapid medical response for emergencies across Zamboanga City and nearby areas. Equipped with life-saving equipment and staffed by trained emergency medical technicians, our ambulances are always ready to provide immediate care during transport.\r\nWhether it is a critical emergency or a scheduled hospital transfer, our team is committed to delivering safe, fast, and compassionate service to every patient.', 'sample\r\nsample', 'saturday to sunday', '/hms/admin/images/services/service_1775403037_719.jpg', '2026-03-08 22:07:46'),
(2, 'Emergency and Trauma Services', 'fa-solid fa-circle-h', 'Our Emergency and Trauma Services operate round the clock to handle life-threatening conditions with speed and precision. Our emergency room is staffed by experienced physicians and nurses trained in advanced trauma care.\r\n\r\nFrom severe injuries to sudden cardiac events, we are equipped to stabilize and treat patients efficiently. We prioritize every case with urgency, ensuring that no patient waits when every second counts.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:07:46'),
(3, 'Renal Services / Dialysis', 'fa-solid fa-syringe', 'Our Renal Services unit provides comprehensive care for patients with kidney disease, including chronic kidney disease management and hemodialysis treatments. Our dialysis center is equipped with modern machines and monitored by nephrologists and trained dialysis nurses.\r\nWe offer a comfortable and safe environment for patients who require regular sessions, with flexible scheduling to accommodate their needs.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:07:46'),
(4, 'Cardiology Services', 'fa-solid fa-heart-pulse', 'Our Cardiology Department delivers specialized care for patients with heart and vascular conditions. Services include ECG, echocardiography, stress testing, cardiac monitoring, and consultations with experienced cardiologists.\r\n\r\nWe are committed to early detection, prevention, and treatment of cardiovascular diseases to help our patients live longer, healthier lives.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:07:46'),
(5, 'Intensive Care Unit', 'fa-solid fa-hand-holding-medical', 'Our Intensive Care Unit provides critical care for patients with severe or life-threatening conditions requiring constant monitoring and advanced medical support. Staffed by intensivists, critical care nurses, and specialists, our ICU is equipped with state-of-the-art monitoring systems and life support equipment.\r\n\r\nEvery patient in our ICU receives individualized care plans tailored to their specific condition and recovery goals.', NULL, NULL, 'admin/images/default.jpg', '2026-03-08 22:07:46'),
(6, 'Rehabilitation Center and Physical Therapy', 'fa-solid fa-person-running', 'Our Rehabilitation Center offers comprehensive physical, occupational, and speech therapy programs designed to help patients recover from injuries, surgeries, strokes, and other medical conditions.\r\n\r\nOur licensed therapists work closely with each patient to restore mobility, strength, and independence. We believe that recovery is a journey, and we are with our patients every step of the way.', NULL, NULL, 'admin/images/default.jpg', '2026-03-08 22:07:46'),
(7, 'Pharmacy Services', 'fa-solid fa-capsules', 'Our in-house pharmacy provides a wide range of medications, medical supplies, and pharmaceutical consultations to both inpatients and outpatients. Staffed by licensed pharmacists, we ensure that prescriptions are dispensed accurately and safely.\r\n\r\nWe also offer patient counseling on proper medication use, potential side effects, and drug interactions to support better health outcomes.', NULL, NULL, 'admin/images/default.jpg', '2026-03-08 22:07:46'),
(8, 'Radiology Services', 'fa-solid fa-x-ray', 'Our Radiology Department is equipped with advanced imaging technology including X-ray, ultrasound, CT scan, and MRI. Our radiologists provide accurate and timely diagnostic readings to support physicians in making informed treatment decisions.\r\n\r\nWe prioritize patient safety and comfort during all imaging procedures, ensuring minimal radiation exposure and a stress-free experience.', NULL, NULL, 'admin/images/default.jpg', '2026-03-08 22:07:46'),
(9, 'Diagnostic and Laboratory Services', 'fa-solid fa-microscope', 'Our clinical laboratory offers a comprehensive range of diagnostic tests including complete blood count, urinalysis, blood chemistry, microbiology, and histopathology. Results are processed with precision and delivered promptly to support accurate diagnoses.\r\n\r\nOur laboratory is accredited and staffed by licensed medical technologists committed to quality and accuracy in every test.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:07:46'),
(10, 'Surgical Services', 'fa-solid fa-mask-face', 'Our Surgical Department handles a wide range of elective and emergency surgical procedures across multiple specialties including general surgery, orthopedics, obstetrics, urology, and more.\r\n\r\nOur operating rooms are equipped with modern surgical equipment and staffed by skilled surgeons, anesthesiologists, and surgical nurses dedicated to patient safety and successful outcomes.', NULL, NULL, 'admin/images/default.jpg', '2026-03-08 22:07:46'),
(11, 'Neonatal Intensive Care Unit (NICU)', 'fa-solid fa-baby', 'Our NICU provides specialized care for premature and critically ill newborns. Equipped with incubators, ventilators, and continuous monitoring systems, our unit is staffed by neonatologists and neonatal nurses trained to handle the most fragile patients.\r\nWe understand how stressful it can be for families during this time, and we are committed to keeping parents informed and involved throughout their baby\'s care journey.', NULL, NULL, '/hms/admin/images/default.jpg', '2026-03-08 22:07:46');

-- --------------------------------------------------------

--
-- Table structure for table `service_photos`
--

CREATE TABLE `service_photos` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_photos`
--

INSERT INTO `service_photos` (`id`, `service_id`, `photo`, `sort_order`, `created_at`) VALUES
(1, 1, '/hms/admin/images/services/svc_gallery_69d91801e0fcc.jpg', 0, '2026-04-10 15:32:17');

-- --------------------------------------------------------

--
-- Table structure for table `siteinfo`
--

CREATE TABLE `siteinfo` (
  `id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siteinfo`
--

INSERT INTO `siteinfo` (`id`, `key_name`, `value`, `updated_at`) VALUES
(1, 'home_tagline', 'Covenant to Heal, Commitment to Care.', '2026-03-15 17:29:54'),
(2, 'home_subtext', 'Serving Our Community with Excellence, Empathy, and Unwavering Commitment to Health', '2026-03-20 07:25:17'),
(3, 'contact_address', 'Veterans Avenue, Zamboanga City', '2026-03-15 19:36:38'),
(4, 'contact_phone', '+639272753097', '2026-03-15 17:07:13'),
(5, 'contact_email', 'zdh1964@yahoo.com', '2026-03-15 17:45:10'),
(6, 'social_facebook', 'https://www.facebook.com/Zamboanga.Doctors', '2026-03-15 17:38:21'),
(91, 'home_bg_image', 'images/home_bg.jpg', '2026-03-31 02:18:41'),
(444, 'whyus_badge_num', '50+', '2026-03-15 19:29:07'),
(445, 'whyus_heading', 'Your Health Is Our, Highest Priority', '2026-03-15 18:34:56'),
(446, 'whyus_desc', 'At Zamboanga Doctors\' Hospital, we combine decades of medical expertise with modern technology and a compassionate team — all dedicated to delivering exceptional care to every patient who walks through our doors.', '2026-03-15 18:34:56'),
(456, 'whyus_image', 'admin/images/why/whyus_bg.png', '2026-04-01 18:57:52'),
(634, 'whyus_image_file', 'C:\\fakepath\\Zamboanga_Doctors_Hospital.JPG', '2026-03-15 19:11:16'),
(1032, 'contact_telephone', '(062)991-1929', '2026-03-15 19:40:50'),
(1043, 'about_para1', 'HISTORY\r\n\r\nThe organization of an additional hospital in Zamboanga City took a long time. For fifty years there was only one government hospital, and one private hospital whose bed capacity were 100 beds and 50 beds respectively, serving the medical needs of an increasing population of Zamboanga City and its neighboring cities and provinces.\r\n\r\nMany felt the need for a new hospital. There was, however, some hesitation to put up another hospital, because it entailed risks, courage, money and public acceptance.  Sometime in 1964, however, in one of the private talks in Zamboanga. Don Pablo Lorenzo, then chairman of the Development Bank of the Philippines remarked: “Que hacen los medicos de Zamboanga para establecer un hospital”. That bolstered the long wish to establish another hospital. So, Dr. Espiridion R. Alvarez started to organize a group to incorporate themselves with the objective of establishing a hospital.\r\n\r\nAfter searching the possible sites for the hospital, the final choice was to purchase the 3,000 sq.m. riceland offered by Mr. R. Santos, along Tumaga Road, now Veterans Avenue. (the hospital started the boom for other land owners to sell their land for commercial buildings) Corporate documents were prepared by Atty. Monico E. Luna. Feasibility studies were finalized by Dr. E. R. Alvarez for a 25-bed capacity hospital. The structural plans were drafted by Mr. Napoleon Concepcion, an architect and Engineer Bert Belciña. Application for funding was made to the DBP. Chairman Don Pablo Lorenzo, his Secretary Mr. Avedillo and Vice-President for real estate and loans Mr. Fabian facilitated and expedited the approval and release of the Php200,000.00 . Engineer Belciña completed the construction of the hospital on July 10, 1966 which was finally blessed by the Rev. Fr. A. Paquia on opening day for the public, on July 11, 1966.', '2026-03-27 12:07:31'),
(1044, 'about_para2', 'After seven years of operation, the 25-bed capacity hospital needed more space and equipment; so another 3-story 60-bed capacity building was urgently needed. Another loan of Php200,000.00 was granted by DPB for its expansion. Mr. Nap Concepcion made the plans and after its approval by the Department of Health hospital division, work on the 3-story building started on October 27, 1972 and completed for occupancy on September 18, 1973. Blessing of the new building was done by Rev. Fr. Ignacio Blanco. The expansion was timely because there was a sudden increase in population in Zamboanga City by refugees and evacuees desiring medical services.\r\n\r\nIn 1997, we saw the groundbreaking of yet another building, which now houses on its ground floor, the Medico Wing, occupied by private physicians of various fields of specialization; on the 2nd floor, the Chapel of the Divine Mercy and a ward of deluxe private rooms and a suite; and on the 3rd floor, another ward of deluxe rooms and the president’s suite.\r\n\r\nToday, Zamboanga Doctors’ Hospital stands as the pillar in this city it serves, having earned as much recognition and respectability from satisfied clients. We have put up another 5-story building, as we are expanding our laboratory, dialysis, ICU, and patients area to meet the medical needs of our increasing population in the city.\r\n\r\nZamboanga Doctors’ Hospital has come a long way from 25 beds in 1966 to 148 beds as of today.', '2026-03-26 08:26:11'),
(1045, 'about_bullet1', 'Quality healthcare services', '2026-03-15 19:53:11'),
(1046, 'about_bullet2', 'Compassionate patient care', '2026-03-15 19:53:11'),
(1047, 'about_bullet3', 'Professional and skilled staff', '2026-03-15 19:53:11'),
(1048, 'about_bullet4', 'Modern facilities and equipment', '2026-03-15 19:53:11'),
(1049, 'about_vision', 'Zamboanga Doctors’ Hospital is a healthcare community of responsive professionals espousing holistic patient and family-centered care with proactive and evolving technologies for cost effective, safe and accessible services encompassing health promotion, disease prevention, Curative and rehabilitative care for healthier and more productive population in Western Mindanao.', '2026-04-10 22:21:08'),
(1050, 'about_mission', 'Towards a palpable participation in the promotion of healthier and more productive population in Western Mindanao, Zamboanga Doctors’ Hospital shall:\r\n- Promote multi-disciplinary approaches to develop competence, integrity and compassion among all  members of the ZDH healthcare professionals. \r\n- Promote the total wellbeing of the patient  through partnering with the patient and the family.\r\n- Provide available, affordable and Obtainable  resources in keeping  with currently acceptable practice in healthcare for patient safety.\r\n- Institute programs that promote health and encourage disease prevention among healthy populations, and restore  optimum health of the sick.', '2026-04-10 22:21:08'),
(1051, 'about_image', 'admin/images/about/about_img.png', '2026-04-01 19:03:58'),
(1310, 'privacy_title', 'Privacy Notice', '2026-03-27 19:40:12'),
(1311, 'privacy_intro', 'Zamboanga Doctors\' Hospital, Inc. is committed to protecting the privacy and confidentiality of your personal and medical information. This notice describes how we collect, use, and safeguard your information.', '2026-03-27 19:40:12'),
(1312, 'privacy_collection', 'We collect personal information including your name, contact details, date of birth, and medical history necessary to provide healthcare services.', '2026-03-27 19:40:12'),
(1313, 'privacy_use', 'Your information is used solely for treatment, payment, and healthcare operations. We do not sell or share your personal information with third parties without your consent, except as required by law.', '2026-03-27 19:40:12'),
(1314, 'privacy_rights', 'You have the right to access, correct, and request deletion of your personal information. Contact our Data Privacy Officer for any concerns.', '2026-03-27 19:40:12'),
(1315, 'privacy_contact', 'For privacy-related concerns, contact our Data Privacy Officer at the hospital\'s main office or via email.', '2026-03-27 19:40:12'),
(1316, 'privacy_updated', '2024-01-01', '2026-03-27 19:40:12'),
(1317, 'terms_title', 'Terms and Conditions', '2026-03-27 19:40:12'),
(1318, 'terms_intro', 'By accessing and using the services of Zamboanga Doctors\' Hospital, Inc., you agree to comply with and be bound by the following terms and conditions.', '2026-03-27 19:40:12'),
(1319, 'terms_services', 'Our hospital provides medical and healthcare services subject to availability, clinical judgment, and applicable laws and regulations of the Philippines.', '2026-03-27 19:40:12'),
(1320, 'terms_liability', 'Zamboanga Doctors\' Hospital shall not be liable for any indirect, incidental, or consequential damages arising from the use of our services beyond what is covered by applicable law.', '2026-03-27 19:40:12'),
(1321, 'terms_payment', 'Payment for services rendered is required in accordance with our billing policies. Accepted payment methods include cash, credit/debit cards, and approved HMO/insurance plans.', '2026-03-27 19:40:12'),
(1322, 'terms_updated', '2024-01-01', '2026-03-27 19:40:12'),
(1323, 'rights_title', 'Patient Rights & Responsibilities', '2026-03-27 19:40:12'),
(1324, 'rights_intro', 'At Zamboanga Doctors\' Hospital, we believe every patient deserves to be treated with dignity, respect, and compassion. The following outlines your rights and responsibilities as our patient.', '2026-03-27 19:40:12'),
(1325, 'rights_r1_title', 'Right to Quality Care', '2026-03-27 19:40:12'),
(1326, 'rights_r1_desc', 'You have the right to receive considerate, respectful, and quality medical care regardless of your background, religion, or financial status.', '2026-03-27 19:40:12'),
(1327, 'rights_r2_title', 'Right to Information', '2026-03-27 19:40:12'),
(1328, 'rights_r2_desc', 'You have the right to be fully informed about your diagnosis, treatment options, risks, and prognosis in a language you understand.', '2026-03-27 19:40:12'),
(1329, 'rights_r3_title', 'Right to Privacy', '2026-03-27 19:40:12'),
(1330, 'rights_r3_desc', 'Your medical records and personal information are kept strictly confidential in accordance with the Data Privacy Act of the Philippines.', '2026-03-27 19:40:12'),
(1331, 'rights_r4_title', 'Right to Consent', '2026-03-27 19:40:12'),
(1332, 'rights_r4_desc', 'You have the right to accept or refuse any treatment after being fully informed of the consequences of your decision.', '2026-03-27 19:40:12'),
(1333, 'rights_r5_title', 'Right to Grievance', '2026-03-27 19:40:12'),
(1334, 'rights_r5_desc', 'You have the right to file a complaint or grievance without fear of retaliation. Contact our Patient Relations Office for assistance.', '2026-03-27 19:40:12'),
(1335, 'rights_resp_intro', 'As a patient, you are also responsible for providing accurate health information, following the prescribed treatment plan, and respecting hospital staff and other patients.', '2026-03-27 19:40:12'),
(1336, 'careers_title', 'Careers at Zamboanga Doctors\' Hospital', '2026-03-27 19:40:12'),
(1337, 'careers_intro', 'Join our team of dedicated healthcare professionals committed to delivering excellent patient care. We are always looking for talented, passionate individuals to grow with us.', '2026-03-27 19:40:12'),
(1338, 'careers_why_title', 'Why Work With Us?.', '2026-04-01 17:52:48'),
(1339, 'careers_why_desc', 'We offer a supportive work environment, competitive compensation, continuous learning opportunities, and the chance to make a real difference in people\'s lives every day.', '2026-03-27 19:40:12'),
(1340, 'careers_email', 'zdh1964@yahoo.com', '2026-03-27 20:29:28'),
(1341, 'careers_note', 'Submit your resume and application letter, or visit the HR Department at the Main Building, 2nd Floor. We will contact shortlisted applicants for an interview.', '2026-03-27 20:49:26'),
(1342, 'org_title', 'Department Organizational Chart', '2026-03-27 19:40:12'),
(1343, 'org_intro', 'The following outlines the organizational structure of Zamboanga Doctors\' Hospital, Inc. and its key departments.', '2026-03-27 19:40:12'),
(1533, 'home_bg_type', 'video', '2026-03-31 07:52:40'),
(1534, 'home_bg_video', 'admin/images/home/home_bg_video.mp4', '2026-03-31 07:52:40'),
(2788, 'org_chart_image', 'images/org_chart.jpg', '2026-04-22 04:33:42');

-- --------------------------------------------------------

--
-- Table structure for table `terms_sections`
--

CREATE TABLE `terms_sections` (
  `id` int(11) NOT NULL,
  `icon` varchar(100) DEFAULT 'fa-solid fa-circle-info',
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `terms_sections`
--

INSERT INTO `terms_sections` (`id`, `icon`, `title`, `content`, `sort_order`, `created_at`) VALUES
(1, 'fa-solid fa-circle-info', 'Introduction', 'By accessing and using the services of Zamboanga Doctors\' Hospital, Inc., you agree to comply with and be bound by the following terms and conditions.', 1, '2026-04-09 18:18:46'),
(2, 'fa-solid fa-hospital', 'Services', 'Our hospital provides medical and healthcare services subject to availability, clinical judgment, and applicable laws and regulations of the Philippines.', 2, '2026-04-09 18:18:46'),
(3, 'fa-solid fa-scale-balanced', 'Liability', 'Zamboanga Doctors\' Hospital shall not be liable for any indirect, incidental, or consequential damages arising from the use of our services beyond what is covered by applicable law.', 3, '2026-04-09 18:18:46'),
(4, 'fa-solid fa-credit-card', 'Payment', 'Payment for services rendered is required in accordance with our billing policies. Accepted payment methods include cash, credit/debit cards, and approved HMO/insurance plans.', 4, '2026-04-09 18:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'zdh1964@yahoo.com', '$2y$10$d40jp4W1RYBXr94HbMQQ/.eIsBSe6fB94V6iUa9.iTdQrI3qdEfIi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_photos`
--
ALTER TABLE `blog_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`);

--
-- Indexes for table `career_jobs`
--
ALTER TABLE `career_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certifications`
--
ALTER TABLE `certifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_photos`
--
ALTER TABLE `facility_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hmos`
--
ALTER TABLE `hmos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_rights`
--
ALTER TABLE `patient_rights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `privacy_sections`
--
ALTER TABLE `privacy_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomrates`
--
ALTER TABLE `roomrates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_photos`
--
ALTER TABLE `room_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_photos`
--
ALTER TABLE `service_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `siteinfo`
--
ALTER TABLE `siteinfo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key_name` (`key_name`);

--
-- Indexes for table `terms_sections`
--
ALTER TABLE `terms_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `blog_photos`
--
ALTER TABLE `blog_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `career_jobs`
--
ALTER TABLE `career_jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certifications`
--
ALTER TABLE `certifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `facility_photos`
--
ALTER TABLE `facility_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `hmos`
--
ALTER TABLE `hmos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_rights`
--
ALTER TABLE `patient_rights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `privacy_sections`
--
ALTER TABLE `privacy_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roomrates`
--
ALTER TABLE `roomrates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `room_photos`
--
ALTER TABLE `room_photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `service_photos`
--
ALTER TABLE `service_photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `siteinfo`
--
ALTER TABLE `siteinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3178;

--
-- AUTO_INCREMENT for table `terms_sections`
--
ALTER TABLE `terms_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_photos`
--
ALTER TABLE `blog_photos`
  ADD CONSTRAINT `blog_photos_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `facility_photos`
--
ALTER TABLE `facility_photos`
  ADD CONSTRAINT `facility_photos_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `room_photos`
--
ALTER TABLE `room_photos`
  ADD CONSTRAINT `fk_room_photos_room` FOREIGN KEY (`room_id`) REFERENCES `roomrates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_photos`
--
ALTER TABLE `service_photos`
  ADD CONSTRAINT `service_photos_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
