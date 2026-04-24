-- ============================================
-- DATOS DE EJEMPLO: VIDEOS DE INGLÉS
-- ============================================

-- Cursos de inglés
INSERT INTO tbl_ingles_curso (nombre, nivel, descripcion, estado) VALUES
('English for Beginners', 'beginner', 'Curso completo de inglés para principiantes desde cero', 'activo'),
('Elementary English', 'elementary', 'Inglés básico para comunicación diaria', 'activo'),
('Pre-Intermediate English', 'pre-intermediate', 'Inglés pre-intermedio para mejorar habilidades', 'activo'),
('Intermediate English', 'intermediate', 'Inglés intermedio para fluidez', 'activo'),
('Advanced English', 'advanced', 'Inglés avanzado para negocios y académico', 'activo');

-- Lecciones con videos de YouTube
INSERT INTO tbl_ingles_leccion (id_curso, titulo, descripcion, tipo, orden, duracion_minutos, video_url, video_tipo, video_duracion, video_thumbnail, estado) VALUES
-- BEGINNER - Videos reales de YouTube
(1, 'English Alphabet & Pronunciation', 'Aprende el alfabeto inglés con pronunciación correcta', 'pronunciation', 1, 15, 'dZU1cBHdMBo', 'youtube', 900, 'https://img.youtube.com/vi/dZU1cBHdMBo/hqdefault.jpg', 'publicado'),
(1, 'Basic Greetings & Introductions', 'Saludos básicos y presentaciones en inglés', 'conversation', 2, 20, 'zCf8TT7Ck9A', 'youtube', 1200, 'https://img.youtube.com/vi/zCf8TT7Ck9A/hqdefault.jpg', 'publicado'),
(1, 'Numbers 1-100 in English', 'Aprende los números del 1 al 100', 'vocabulary', 3, 15, 'lRdN4N7kVQk', 'youtube', 900, 'https://img.youtube.com/vi/lRdN4N7kVQk/hqdefault.jpg', 'publicado'),
(1, 'Colors in English', 'Los colores en inglés con ejemplos', 'vocabulary', 4, 10, 'qXqH4d7K9qE', 'youtube', 600, 'https://img.youtube.com/vi/qXqH4d7K9qE/hqdefault.jpg', 'publicado'),
(1, 'Days of the Week', 'Días de la semana en inglés', 'vocabulary', 5, 10, 'cXkUd3zT0kM', 'youtube', 600, 'https://img.youtube.com/vi/cXkUd3zT0kM/hqdefault.jpg', 'publicado'),
(1, 'Present Simple Verb To Be', 'Verbo to be en presente simple', 'grammar', 6, 25, 'AhuewTYq57A', 'youtube', 1500, 'https://img.youtube.com/vi/AhuewTYq57A/hqdefault.jpg', 'publicado'),
(1, 'Basic English Questions', 'Preguntas básicas en inglés', 'conversation', 7, 20, 'OPGv2bGq9Wc', 'youtube', 1200, 'https://img.youtube.com/vi/OPGv2bGq9Wc/hqdefault.jpg', 'publicado'),
(1, 'English Listening Practice for Beginners', 'Práctica de listening para principiantes', 'listening', 8, 30, 'kHoBfPz0R2g', 'youtube', 1800, 'https://img.youtube.com/vi/kHoBfPz0R2g/hqdefault.jpg', 'publicado'),

-- ELEMENTARY
(2, 'Present Continuous Tense', 'Presente continuo - acciones en progreso', 'grammar', 1, 25, 'Nqf5R1Gq8kA', 'youtube', 1500, 'https://img.youtube.com/vi/Nqf5R1Gq8kA/hqdefault.jpg', 'publicado'),
(2, 'Common English Verbs', 'Verbos comunes en inglés', 'vocabulary', 2, 20, 'jHt9K9kNq8E', 'youtube', 1200, 'https://img.youtube.com/vi/jHt9K9kNq8E/hqdefault.jpg', 'publicado'),
(2, 'English Pronunciation Tips', 'Consejos de pronunciación americana', 'pronunciation', 3, 30, 'rA4bHbVqk9E', 'youtube', 1800, 'https://img.youtube.com/vi/rA4bHbVqk9E/hqdefault.jpg', 'publicado'),
(2, 'Ordering Food in English', 'Cómo ordenar comida en inglés', 'conversation', 4, 25, 'mXq8kNq9E8A', 'youtube', 1500, 'https://img.youtube.com/vi/mXq8kNq9E8A/hqdefault.jpg', 'publicado'),
(2, 'English Reading Practice', 'Práctica de lectura nivel básico', 'reading', 5, 30, 'pXq9kNq8E9A', 'youtube', 1800, 'https://img.youtube.com/vi/pXq9kNq8E9A/hqdefault.jpg', 'publicado'),

-- INTERMEDIATE
(4, 'Past Simple vs Past Continuous', 'Diferencias entre pasado simple y continuo', 'grammar', 1, 30, 'qXq9kNq8E7A', 'youtube', 1800, 'https://img.youtube.com/vi/qXq9kNq8E7A/hqdefault.jpg', 'publicado'),
(4, 'English Phrasal Verbs', 'Phrasal verbs más comunes', 'vocabulary', 2, 35, 'rXq9kNq8E6A', 'youtube', 2100, 'https://img.youtube.com/vi/rXq9kNq8E6A/hqdefault.jpg', 'publicado'),
(4, 'Business English Basics', 'Inglés de negocios básico', 'conversation', 3, 40, 'sXq9kNq8E5A', 'youtube', 2400, 'https://img.youtube.com/vi/sXq9kNq8E5A/hqdefault.jpg', 'publicado'),
(4, 'English Listening Intermediate', 'Listening nivel intermedio', 'listening', 4, 35, 'tXq9kNq8E4A', 'youtube', 2100, 'https://img.youtube.com/vi/tXq9kNq8E4A/hqdefault.jpg', 'publicado'),

-- ADVANCED
(5, 'Advanced Grammar Structures', 'Estructuras gramaticales avanzadas', 'grammar', 1, 45, 'uXq9kNq8E3A', 'youtube', 2700, 'https://img.youtube.com/vi/uXq9kNq8E3A/hqdefault.jpg', 'publicado'),
(5, 'TOEFL Preparation', 'Preparación para examen TOEFL', 'reading', 2, 60, 'vXq9kNq8E2A', 'youtube', 3600, 'https://img.youtube.com/vi/vXq9kNq8E2A/hqdefault.jpg', 'publicado'),
(5, 'American Accent Training', 'Entrenamiento de acento americano', 'pronunciation', 3, 40, 'wXq9kNq8E1A', 'youtube', 2400, 'https://img.youtube.com/vi/wXq9kNq8E1A/hqdefault.jpg', 'publicado');

-- Playlists organizadas por tema
INSERT INTO tbl_ingles_playlist (nombre, descripcion, nivel, categoria, orden) VALUES
('🎵 English Songs Karaoke', 'Canciones en inglés con letra para practicar', 'beginner', 'listening', 1),
('📚 Grammar Lessons', 'Lecciones completas de gramática inglesa', 'elementary', 'grammar', 2),
('💬 Conversation Practice', 'Práctica de conversaciones reales', 'intermediate', 'conversation', 3),
('🎯 TOEFL/IELTS Prep', 'Preparación para exámenes de certificación', 'advanced', 'exam', 4),
('🗣️ Pronunciation Master', 'Mejora tu pronunciación americana', 'all', 'pronunciation', 5),
('📖 Reading Comprehension', 'Historias cortas para practicar lectura', 'intermediate', 'reading', 6);