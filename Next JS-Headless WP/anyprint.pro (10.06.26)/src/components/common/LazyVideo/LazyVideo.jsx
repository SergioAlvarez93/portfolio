import { useState, useRef } from 'react';
import styles from './LazyVideo.module.scss'
import Image from 'next/image';

export const LazyVideo = ({video, poster, posterAlt, className=""}) => {
    const [isPlaying, setPlaying] = useState(false);
    const videoRef = useRef(null);

    const handlePlay = () => {
        setPlaying(true);
        setTimeout(() => {
            videoRef.current?.play();
        }, 0);
    };
    
    if (!isPlaying) {
        return (
            <div className={className} onClick={handlePlay}>
                <Image 
                    src={poster} 
                    alt={posterAlt} 
                    sizes='992px'
                    fill
                    className={styles['img']}
                />
                <Image src="/play_btn.webp" alt="Кнопка Play" fill sizes='90px' className={styles['play']}></Image>
            </div>
        )
    }

    return (
        <video ref={videoRef} className={className} controls autoPlay playsInline preload="none">
            <source src={video} type="video/mp4" />
            Ваш браузер не поддерживает видео.
        </video>
    )
}