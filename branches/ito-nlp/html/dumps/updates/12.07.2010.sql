ALTER TABLE  `schools_rate` ADD  `rate` INT( 1 ) NOT NULL AFTER  `user_id` ;

INSERT INTO `courses` (`id`, `caption`, `description`, `level`, `alias`, `avatar`, `crdate`, `modified`, `school_id`, `fee_id`, `base_fee`, `rate`) VALUES
(17, 'ONLINE AUNLP NLP PRACTITIONER CERTIFICATION COURSE', '<p>NLP Class and Classes, Training and Certification Program Online and in Savannah, Georgia (GA), Los Angeles, California (CA), and New York (NY) by Steve G. Jones, NLP Master Trainer.</p>\r\n<p>\r\nDon''t be misled by other programs padded out to 300 hours - this industry is self-regulating and the schools are ultimately businesses\r\nSteve G. Jones strips out all the unnecessary time wasting & just presents to you all the information you need to practice safely, successfully and professionally\r\n</p>\r\n<p>By completing this course, you will also have lifetime support from Steve G. Jones and the American University of NLP</p>', 1, 'AUNLP', 'storage/uploads/courses/AUNLP/avatar.jpg', '2010-07-09 11:44:32', '0000-00-00 00:00:00', 2, 0, 0, 0);

ALTER TABLE  `exercises` CHANGE  `description`  `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

INSERT INTO `exercises` (`id`, `caption`, `description`, `crdate`, `modified`, `owner_id`, `rate`, `course_id`, `video`) VALUES
(23, 'NLP Practitioner Class sample 1', '<p>Now imagine�communicating, connecting with people in such a way that where what you say, how you say it -- what you do, how you do it will be expressed and done with excellence for excellent results! In NLP training, the more you prepare and learn, the firmer your grasp of how to model excellence. The more you train and prepare, the more secure your footing in how to actually walk in excellence--for the successful out comes that you want --whether you are a sales associate, a lawyer, doctor, teacher, or entrepreneur. Perhaps you don''t work at all --still, you deal with people each day. And just because you don''t draw a paycheck does not make your encounters with people any less valuable. Whatever your goals are, NLP training -- which gives you the ropes in how to model excellence -- can only benefit you.</p>\r\n\r\n<p>NLP, as you''ve probably read in the Basic Practitioner link (or if you''ve taken my training) is short for Neuro-Linguistic Programming. This "programming" is a set of communication and thinking s', '2010-07-09 11:52:47', '0000-00-00 00:00:00', 37, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/F5g19XUSKdo&amp;hl=ru_RU&amp;fs=1?rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/F5g19XUSKdo&amp;hl=ru_RU&amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>'),
(24, 'NLP Practitioner Class sample 2', '<p>Now imagine�communicating, connecting with people in such a way that where what you say, how you say it -- what you do, how you do it will be expressed and done with excellence for excellent results! In NLP training, the more you prepare and learn, the firmer your grasp of how to model excellence. The more you train and prepare, the more secure your footing in how to actually walk in excellence--for the successful out comes that you want --whether you are a sales associate, a lawyer, doctor, teacher, or entrepreneur. Perhaps you don''t work at all --still, you deal with people each day. And just because you don''t draw a paycheck does not make your encounters with people any less valuable. Whatever your goals are, NLP training -- which gives you the ropes in how to model excellence -- can only benefit you.</p>\r\n\r\n<p>NLP, as you''ve probably read in the Basic Practitioner link (or if you''ve taken my training) is short for Neuro-Linguistic Programming. This "programming" is a set of communication and thinking skills used by people all over the world. </p>\r\n\r\n<p>NLP training was started in 1975 by a mathematician and a linguist who had a very powerful question: could you study people with a highly developed skill and discover a way to transfer that skill to others? Richard Bandler and John Grinder explored this question and from there successfully developed NLP, which stands for Neuro-Linguistic Programming. Neuro refers to the brain and nervous system, all of which is powered by sensory in-put-what we see, hear, feel, smell, and taste. Linguistic refers to language, or the packaging of our thoughts. Programming is the arrangement of sequence and data. </p>\r\n\r\n<p>The cultivating of excellence will only enhance and develop your own individual giftings and skills-and your uniqueness. It will enable you to operate at your personal best-not compromising who you are or your belief system. \r\n</p>\r\n<p>If anything NLP training-the Basic Training and the Master Practitioner Training -will encourage you to be you, expressing yourself in the best way-for the best outcomes! Mastery of this will come from practice and more practice, along with more training. </p>\r\n\r\n<p>This training for the Master Practitioner Training will give you such mastery in skills for greater successful outcomes. Let''s take a sneak preview of a few of the things on which the Master Practitioner Training will touch . . .</p>', '2010-07-09 11:57:51', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/rakaCf5AxIQ&amp;hl=ru_RU&amp;fs=1?rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/rakaCf5AxIQ&amp;hl=ru_RU&amp;fs=1?rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="640" height="385"></embed></object>'),
(25, 'NLP Practitioner Visual Squash sample 1', '<p>The visual squash is a powerful tool of neuro linguistic programming to integrate different "parts" of a client, that we teach here on our NLP training in New York.</p>\r\n<p>The visual squash of NLP is a technique designed to integrate "parts" of a client. This is simply a way of saying we use it when a client says something like "on one hand I want this, but on the other hand I want that".</p>\r\n<p>The outcome of the visual squash</p>\r\n<p>It is not unusual following this exercise for the client to find a solution that allows them to satisfy both positive intentions, ie to have excitement and safety.</p>', '2010-07-09 11:59:58', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/xsogWQdwuAM&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/xsogWQdwuAM&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(26, 'NLP Practitioner Visual Squash sample 2', '<p>The visual squash is a powerful tool of neuro linguistic programming to integrate different \\"parts\\" of a client, that we teach here on our NLP training in New York.</p>\r\n<p>The visual squash of NLP is a technique designed to integrate \\"parts\\" of a client. This is simply a way of saying we use it when a client says something like \\"on one hand I want this, but on the other hand I want that\\".</p>\r\n<p>The outcome of the visual squash</p>\r\n<p>It is not unusual following this exercise for the client to find a solution that allows them to satisfy both positive intentions, ie to have excitement and safety.</p>', '2010-07-09 12:00:31', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/Ibm8hzBVRds&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/Ibm8hzBVRds&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(27, 'NLP Practitioner Swish Pattern 1', '<p>The swish pattern uses rapid-fire submodality shifts to associate two mental constructs so that one automatically leads to the other.</p>\r\n\r\n<p>Example</p>\r\n\r\n<p>Guru wants to get in shape. His only problem is an ice cream truck that swings by his village every day at noon. So far, every time Guru''s seen the rasberry-vanilla ice cream cone printed on the side of the truck, he''s felt he had to buy one.</p>\r\n\r\n<p>Guru realizes that one way to change his behavior is a swish pattern. He closes his eyes, and pictures the rasberry-vanilla ice cream cone right in front of him. He puts an image of himself with the body he''s always wanted off in the distance. Now he pushes the cone off to the horizon and snaps the picture of himself into its place as fast as he can.</p>\r\n\r\n<p>After doing this a few times, he brings up the image of the cone. Before he can think about it, the new image of his ultra-buff body pops into his head.</p>\r\n\r\n<p>Now when Guru sees the ice cream truck, he instantly remembers this image. The thought of buying ice cream no longer even occurs to him!</p>', '2010-07-09 12:02:34', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/rniDKcqCtMw&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/rniDKcqCtMw&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(28, 'NLP Practitioner Swish Pattern 2', '<p>The swish pattern uses rapid-fire submodality shifts to associate two mental constructs so that one automatically leads to the other.</p>\r\n\r\n<p>Example</p>\r\n\r\n<p>Guru wants to get in shape. His only problem is an ice cream truck that swings by his village every day at noon. So far, every time Guru\\''s seen the rasberry-vanilla ice cream cone printed on the side of the truck, he\\''s felt he had to buy one.</p>\r\n\r\n<p>Guru realizes that one way to change his behavior is a swish pattern. He closes his eyes, and pictures the rasberry-vanilla ice cream cone right in front of him. He puts an image of himself with the body he\\''s always wanted off in the distance. Now he pushes the cone off to the horizon and snaps the picture of himself into its place as fast as he can.</p>\r\n\r\n<p>After doing this a few times, he brings up the image of the cone. Before he can think about it, the new image of his ultra-buff body pops into his head.</p>\r\n\r\n<p>Now when Guru sees the ice cream truck, he instantly remembers this image. The thought of buying ice cream no longer even occurs to him!</p>', '2010-07-09 12:02:52', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/tQDc8LxFym4&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/tQDc8LxFym4&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(29, 'NLP Practitioner VAKOG sample', '<p>In NLP training, we call the world that we express ourselves internally and externally, the VAKOG (visual , auditory , kinaesthetic , olfactory and gustatory ). The latter two there refer to smell and taste. Later in this piece we give you a test to see what your preferences are..</p>\r\n\r\n\r\n<p>Even if you haven''t undertaken NLP training in any form it''s easy to realise that people have very different ways of describing their world. From noticing the eye patterns and checking that people have varying ways of internalising information, we can begin to know that everybody has their own personal way of experiencing their world.</p>\r\n\r\n<p>So how do we begin to recognize how each person makes sense of this unique world in which they live?</p>\r\n\r\n<p>One of the easiest ways to discover this is to simply listen to the words that they''re using. In NLP, we call this the VAKOG, (the representation system), which stands for visual, auditory, kinaesthetic, olfactory, and gustatory. The other system, which also plays a part in this, we call self talk, the labeling system, or for short, Ai (audio internal).</p>\r\n\r\n<p>According to NLP, for many practical purposes mental processing of events and memories can be treated as if performed by the five senses. For example, Einstein credited his discovery of spacial relativity to a mental visualization of "sitting on the end of a ray of light", but many people as part of decision-making talk to themselves in their heads and won�t be making pictures at all.</p>\r\n<p>The manner in which this is done, and the effectiveness of the mental strategy employed, plays a critical part in the way mental processing takes place.</p>', '2010-07-09 12:05:04', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/l0Dij3w5PgU&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/l0Dij3w5PgU&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(30, 'NLP Practitioner Changing Personal History sample', '<p>Change personal History is for the purpose of changing a number of memories in the past and adding resources. It has been replaced by TIME Techniques.</p>\r\n\r\n<p>Design and install a positive resource anchor.</p>\r\n<p>Identify with client a persistent recurring undesireable state, and anchor the state.</p>\r\n<p>Fire the undesireable state anchor while you identify and then anchor one event in the client''s past where the client experienced the state.</p>\r\n<p>Repeat this, anchoring at least two more events. (Anchor as many as necessary.)</p>\r\n<p>Make sure that the state associated with the positive resource anchor is greater than the negative state.</p>\r\n<p>Fire the first event anchor while holding the resource anchor and have the client relive the event with the new resources.</p>\r\n<p>Repeat this for each event that was anchored.</p>\r\n<p>Test.</p>\r\n<p>Future Pace.</p>', '2010-07-09 12:08:03', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/B56iChvfekE&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/B56iChvfekE&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(31, 'NLP Practitioner Meta Model sample', '<p>The meta-model (initially named meta-model of therapy[1] and also known as meta-model of language[2]) is a pragmatic communications model used to specify information in a speaker''s language. It is often contrasted with the intentionally ambiguous Milton Erickson inspired-Milton model. The meta model was originally presented in The Structure of Magic I: A Book About Language and Therapy in 1975[1] by Richard Bandler and linguist John Grinder, the co-founders of neuro-linguistic programming, who collaborated between 1973 and 1975.</p>\r\n<p>The authors were particularly interested in the patterns of language and behavior that effective psychotherapists used with their clients to effect change.[1] They observed and imitated gestalt therapist Fritz Perls and family systems therapist Virginia Satir in person and via recordings. The authors cited Noam Chomsky''s transformational syntax, which was John Grinder''s linguistics specialization, and ideas about human modeling from the work of Alfred Korzybski as being influential in their thinking. Of particular interest was Korzybski''s critique of cause-effect rationale and his notion that "the map is not the territory" which also featured in Gregory Bateson''s writing.</p>', '2010-07-09 12:09:29', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/15n7DD03NrU&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/15n7DD03NrU&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(32, 'NLP Practitioner Anchoring sample', '<p>Anchoring is reminiscent of Pavlov''s experiments with dogs. Pavlov sounded a bell as the animal was given food. The animals salivated when they saw the food. After some parings of the bell and the food, the bell alone elicited salivation.</p>\r\n\r\n<p>Anchors are stimuli that call forth states of mind - thoughts and emotions. For example, touching a knuckle of the left hand could be an anchor. Some anchors are involuntary. So the smell of bread may take you back to your childhood. A tune may remind you of a certain person. A touch can bring back memories and the past states. These anchors work automatically and you may not be aware of the triggers.</p>\r\n\r\n<p>Establishing an anchor means producing the stimuli (the anchor) when the resourceful state is experienced so that the resourceful state is pared to the anchor. For example, touching the knuckle of the left hand when the resourceful state is experienced to pair the two events.</p>\r\n\r\n<p>Activating or firing the anchor means producing the anchor after it has been conditioned so that the resourceful state occurs.For example, touching the knuckle of the left hand after the anchor has been established so that this action produced the resourceful state.</p>\r\n\r\n<p>This page is concerned with creating anchors that produce resourceful states at will.</p>', '2010-07-09 12:10:39', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/BMPFRnulNT8&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/BMPFRnulNT8&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(33, 'NLP Practitioner Anchors sample', '<p>Automatic Unconscious Anchors</p>\r\n\r\n<p>We are affected by anchors throughout our lives and go into a good mood or a bad one ... feel motivated to do one thing or to do another ... feel confident and resourceful or the opposite. We are responding to anchors, but we may not know what they are.</p>\r\n\r\n<p>These anchors have been built up accidentally. In fact, we often think that our mood has nothing to do with us and that our moods occur by chance.</p>\r\n\r\n<p>Designer Anchors</p>\r\n\r\n<p>Designer anchors are what this page is about. You use them to produce the state of mind or mood you need for a given situation. You enter an interview calm and relaxed. You control your temper. You turn on the enthusiasm you need to do a task.</p>\r\n\r\n<p>First of all we will assemble the ingredients for anchors and then give the whole procedure for establishing your designer anchors. You can use any resourceful state, but here we will us ''being calm and relaxed'' as the example.</p>\r\n\r\n<p>Although we have dealth with the subject of establishing anchors in some depth in this page, it is actually extremely easy to establish them!</p>', '2010-07-09 12:12:09', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/zQm89TZEDak&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/zQm89TZEDak&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>'),
(34, 'NLP Practitioner Representational Systems sample', '<p>Representational systems (also known as sensory modalities and abbreviated to VAKOG or known as the 4-tuple) is a neuro-linguistic programming model that examines how the human mind processes information. It states that for practical purposes, information is (or can be treated as if) processed through the senses. Thus people say one talks to oneself (the auditory sense) even if no words are emitted, one makes pictures in one''s head when thinking or dreaming (the visual sense), and one considers feelings in the body and emotions (known as the kinesthetic sense).</p>\r\n<p>NLP holds it as crucial in human cognitive processing to recognize that the subjective character of experience is strongly tied into, and influenced by, how memories and perceptions are processed within each sensory representation in the mind. It considers that expressions such as "It''s all misty" or "I can''t get a grip on it", can often be precise literal unconscious descriptions from within those sensory systems, communicating unconsciously where the mind perceives a problem in handling some mental event.</p>\r\n<p>Within NLP, the various senses in their role as information processors, are known as representation systems, or sensory modalities. The model itself is known as the VAKOG model (from the initial letters of the sensory-specific modalities: visual, auditory, kinesthetic, olfactory, gustatory). Since taste and smell are so closely connected, sometimes as a 4-tuple, meaning its 4 way sensory-based description. A submodality is a structural element of a sensory impression, such as its perceived location, distance, size, or other quality.</p>\r\n<p>Representational systems and submodalities are seen in NLP as offering a valuable therapeutic insight (or metaphor) and potential working methods, into how the human mind internally organizes and subjectively attaches meaning to events.</p>', '2010-07-09 12:13:54', '0000-00-00 00:00:00', 40, 0, 17, '<object width="640" height="385"><param name="movie" value="http://www.youtube.com/v/j6u2htICCII&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.youtube.com/v/j6u2htICCII&color1=0xb1b1b1&color2=0xd0d0d0&hl=en_US&feature=player_embedded&fs=1" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="640" height="385"></embed></object>');