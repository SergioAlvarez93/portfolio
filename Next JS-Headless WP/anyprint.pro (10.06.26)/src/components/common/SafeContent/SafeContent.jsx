'use client';

import DOMPurify from 'isomorphic-dompurify';

export default function SafeContent({
  html,
  className = '',
  tag: Tag = 'span',
  fallback = null,
}) {
  
  if (!html?.trim()) {
    return <>{fallback}</>;
  }

  const cleanHtml = DOMPurify.sanitize(html, {
    ALLOWED_TAGS: ['span'],
    ALLOWED_ATTR: ['style', 'class'],
    ALLOWED_STYLES: {
      '*': {
        'color': [/^#([0-9a-fA-F]{3,6})$/i, /^rgb/, /^rgba/],
        'font-weight': [/^bold$|^700$|^600$|^500$|^400$/],
        'font-style': [/^italic$|^oblique$/],
      }
    },
    FORBID_TAGS: ['*'],
    FORBID_ATTR: ['onclick', 'onerror', 'onload', 'href', 'src', 'javascript', 'target'],
    SANITIZE_DOM: true,
  });

  return (
    <Tag 
      className={className}
      dangerouslySetInnerHTML={{ __html: cleanHtml }} 
    />
  );
}